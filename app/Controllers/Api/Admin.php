<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class Admin extends BaseController
{
    public function __construct()
    {
        $this->req = \Config\Services::request();
        $this->masterModel = new \App\Models\MasterModel();
        $this->db = \Config\Database::connect();
    }

    private function dataTables($option, $array = false)
    {
        try {
            $this->masterModel->table = $option['table'] ?? '';
            $this->masterModel->columnOrder = $option['columnOrder'] ?? [];
            $this->masterModel->columnSearch = $option['columnSearch'] ?? [];
            $this->masterModel->selectData = $option['selectData'] ?? '';
            $this->masterModel->tableJoin = $option['join'] ?? [];
            $this->masterModel->order = $option['order'] ?? ['id' => 'desc'];
            $this->masterModel->whereData = $option['whereData'] ?? [];
            $this->masterModel->groupByData = $option['groupByData'] ?? [];
            $field = $option['field'] ?? [];
            $listData = $this->masterModel->get_datatables();
            // echo $this->db->getLastQuery();
            $data = [];
            foreach ($listData as $field_) {
                $row = [];
                $row['id'] = Enc($field_['id']);
                foreach ($field as $key) {
                    $row[$key] = $field_[$key];
                }
                $data[] = $row;
            }
            $draw = isset($_POST['draw']) ? $_POST['draw'] : null;
            $output = [
                'draw' => $draw,
                'recordsTotal' => $this->masterModel->count_all(),
                'recordsFiltered' => $this->masterModel->count_filtered(),
                'data' => $data,
            ];
            $result = $output;
        } catch (\Throwable $th) {
            $result = [
                'status' => 'fail',
                'message' => $th->getMessage() . ', Line : ' . $th->getLine() . ', File : ' . $th->getFile() . ', Query : ' . $this->db->getLastQuery(),
            ];
        } catch (\Exception $ex) {
            $result = [
                'status' => 'fail',
                'message' => $ex->getMessage(),
            ];
        } finally {
            if ($array == true) return $result;
            echo json_encode($result);
        }
    }

    private function getRowTable($option = ['table' => '', 'select' => '', 'where' => [], 'guard' => []], $array = false)
    {
        try {
            $data = $this->db->table($option['table'])->select(isset($option['select']) ? $option['select'] : '*')->where($option['where'])->get()->getRowArray();
            if (!$data) {
                throw new \Exception('no data');
            }
            $guard = ['id:hash', 'token', 'password'];
            if (!empty($option['guard'])) {
                $guard = array_merge($guard, $option['guard']);
            }

            $data = Guard($data, $guard);

            $result = [
                'status' => 'ok',
                'data' => $data,
            ];
        } catch (\Throwable $th) {
            $result = [
                'status' => 'fail',
                'message' => $th->getMessage(),
            ];
        } catch (\Exception $ex) {
            $result = [
                'status' => 'fail',
                'message' => $ex->getMessage(),
            ];
        } finally {
            if ($array == true) return $result;
            echo json_encode($result);
        }
    }

    public function getDataOption($data = '')
    {
        try {
            if ($data == '') {
                throw new \Exception('no param');
            }
            $table = [
                'users' => [
                    'table' => 'users u',
                    'select' => 'u.id, u.nama, u.nip, ja.nama jabatan, ja.visible_all, u.unit_kerja_id',
                    'protected' => ['id:hash'],
                    'join' => [
                        'jabatan ja' => 'ja.id = u.jabatan_id'
                    ]
                ],
                'jabatan' => [
                    'table' => 'jabatan',
                    'protected' => ['id:hash'],
                ],
                'unit-kerja' => [
                    'table' => 'unit_kerja',
                    'protected' => ['id:hash'],
                ],
            ];
            if (!array_key_exists($data, $table)) {
                throw new \Exception('nothing there');
            }
            $builder = $this->db->table($table[$data]['table']);

            if (!empty($table[$data]['join'])) {
                foreach ($table[$data]['join'] as $key => $value) {
                    $builder->join($key, $value, 'left');
                }
            }

            if (!empty($table[$data]['select'])) {
                $builder->select($table[$data]['select']);
            }

            if (isset($_REQUEST['where'])) {
                $builder->where($_REQUEST['where']);
            }
            if (isset($_REQUEST['order'])) {
                $builder->orderBy(key($_REQUEST['order']), $_REQUEST['order'][key($_REQUEST['order'])]);
            }
            $data_ = $builder->get()->getResultArray();

            if ($data == 'users') {
                $dataApproval3 = $this->db->table('users u')
                    ->select('u.id, u.nama, u.nip, ja.nama jabatan, ja.visible_all, u.unit_kerja_id')
                    ->join('jabatan ja', 'ja.id = u.jabatan_id', 'left')
                    ->where('visible_all', '1')
                    ->get()->getResultArray();
                $data_ = array_unique(array_merge($data_, $dataApproval3), SORT_REGULAR);
            }

            $resultData = [];
            foreach ($data_ as $rows) {
                $rows = Guard($rows, $table[$data]['protected']);
                unset($rows['created_at']);
                $resultData[] = $rows;
            }
            $result = [
                'status' => 'ok',
                'data' => $resultData,
            ];
        } catch (\Throwable $th) {
            $result = [
                'status' => 'fail',
                'message' => $th->getMessage(),
            ];
        } catch (\Exception $ex) {
            $result = [
                'status' => 'fail',
                'message' => $ex->getMessage(),
            ];
        } finally {
            echo json_encode($result);
        }
    }

    public function dataPegawai()
    {
        return $this->dataTables([
            'table' => 'pegawai_pegawai p',
            'selectData' => '
                p.id, p.nama_pegawai as nama_pegawai,
                kat.jabatan_kategori as jabatan_kategori, 
                p.alamat_pegawai as alamat,
                kat.nama_kategori as nama_kategori
            ',
            'field' => ['nama_pegawai', 'jabatan_kategori', 'alamat', 'nama_kategori'],
            'columnOrder' => [null, 'nama_pegawai', 'jabatan_kategori', 'alamat', 'nama_kategori'],
            'columnSearch' => ['p.nama_pegawai', 'kat.jabatan_kategori', 'p.alamat_pegawai', 'kat.nama_kategori'],
            'join' => [
                'pegawai_kategori kat' => 'p.id_kategori = kat.id',
            ],
            'order' => ['p.id' => 'desc'],
        ]);
    }

    public function getRowPegawai($id)
    {
        return $this->getRowTable([
            'table' => 'pegawai_pegawai',
            'where' => [EncKey('id') => $id],
            'guard' => [
                'id_kategori:hash'
            ],
        ]);
    }
}