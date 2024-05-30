<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Epresence;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\EpresenceResource;
use Illuminate\Support\Facades\Validator;

class EpresenceController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $data = DB::table('epresences')
            ->join('users', 'epresences.user_id', '=', 'users.id')
            ->select(
                'users.id as id_user',
                'users.name as nama_user',
                DB::Raw('DATE(waktu) as tanggal'),
                DB::Raw('waktu:: timestamp:: time as waktu_masuk'),
                DB::Raw("(SELECT waktu::timestamp:: time as waktu_pulang 
                FROM epresences as t2 
                WHERE t2.user_id = users.id 
                AND t2.type = 'OUT' 
                AND DATE(t2.waktu) = DATE(epresences.waktu) 
                AND t2.user_id = $userId) as waktu_pulang"),
                DB::Raw("CASE WHEN is_approve THEN 'APPROVE' ELSE 'REJECT' END as status_masuk"),
                DB::Raw("(SELECT CASE WHEN is_approve THEN 'APPROVE' ELSE 'REJECT' END as status 
                FROM epresences as t2 
                WHERE t2.user_id = users.id 
                AND t2.type = 'OUT' 
                AND DATE(t2.waktu) = DATE(epresences.waktu) 
                AND t2.user_id = $userId) as status_pulang"),
            )
            ->where('epresences.type', 'IN')
            ->where('epresences.user_id', $userId)
            ->get();

        return response()->json([
            'message' => "Success get data",
            'data'    => $data,
            'status' => true,
            'errors' => []
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->only(['type', 'waktu']), [
            'type' => ['required', 'in:IN,OUT'],
            'waktu' => ['required', 'date_format:Y-m-d H:i:s'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
                'data'    => null,
                'status'  => false,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $epresence = Epresence::create([
                'user_id' => $request->user()->id,
                'type'    => $request->type,
                'waktu'   => $request->waktu,
            ]);

            return response()->json([
                'message' => "Epresence $epresence->type success",
                'data'    => new EpresenceResource($epresence),
                'status' => true,
                'errors' => []
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => "Error Internal Serve",
                'errors'  => [$e->getMessage()],
                'data'    => null,
                'status'  => false,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->only(['is_approve']), [
            'is_approve' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
                'data'    => null,
                'status'  => false,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $epresence = Epresence::find($id);
            if (!$epresence) {
                return response()->json(
                    [
                        'message' => "Epresence not found!",
                        'errors'  => [],
                        'data'    => null,
                        'status'  => false,
                    ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }
            $epresence->is_approve = $request->is_approve;
            $epresence->save();

            return response()->json([
                'message' => "Update status success",
                'data'    => null,
                'status'  => true,
                'errors'  => []
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => "Error Internal Serve",
                'errors'  => [$e->getMessage()],
                'data'    => null,
                'status'  => false,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
