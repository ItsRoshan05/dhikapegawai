<?php

use CodeIgniter\Model as db;

function Create($table, $data, $json = false)
{
    try {
        $CI = new db();
        if (isMultiArray($data)) {
            for ($i = 0; $i < count($data); ++$i) {
                $dataBaru[] = array_merge($data[$i], [
                    'created_at' => DATE_NOW,
                    'updated_at' => DATE_NOW,
                ]);
            }
            $CI->db->table($table)->insertBatch($dataBaru);
        } else {
            $data = array_merge($data, [
                'created_at' => DATE_NOW,
                'updated_at' => DATE_NOW,
            ]);
            $CI->db->table($table)->insert($data);
        }
        $return_ = $CI->db->affectedRows();
        if ($return_ == false) {
            throw new Exception('Gagal memasukan data');
        }
        $message = [
            'status' => 'ok',
            'message' => 'Berhasil memasukan data',
        ];
    } catch (\Throwable $th) {
        $message = [
            'status' => 'fail',
            'message' => $th->getMessage(),
        ];
    } catch (\Exception $ex) {
        $message = [
            'status' => 'fail',
            'message' => $ex->getMessage(),
        ];
    } finally {
        if ($json == true) {
            echo json_encode($message);
        } else {
            return $return_ ?? false;
        }
    }
}

function Where($table, $where)
{
    $CI = new db();
    $data = $CI->db->table($table)->where($where)->get()->getRowArray();

    return $data;
}

function Update($table, $data, $where, $json = false)
{
    try {
        $CI = new db();
        $tampungGetData = $CI->db->table($table)->where($where)->get()->getRowArray();
        $cekEdit = 0;
        foreach ($data as $key => $value) {
            if ($tampungGetData[$key] != $value) {
                ++$cekEdit;
            }
        }
        if ($cekEdit == 0) {
            return false;
        }
        $data = array_merge($data, [
            'updated_at' => DATE_NOW,
        ]);
        $CI->db->table($table)->set($data)->where($where)->update();
        $return_ = $CI->db->affectedRows();
        if ($return_ == false) {
            throw new Exception('Gagal mengupdate data');
        }
        $message = [
            'status' => 'ok',
            'message' => 'Berhasil mengupdate data',
        ];
    } catch (\Throwable $th) {
        $message = [
            'status' => 'fail',
            'message' => $th->getMessage(),
        ];
    } catch (\Exception $ex) {
        $message = [
            'status' => 'fail',
            'message' => $ex->getMessage(),
        ];
    } finally {
        if ($json == true) {
            echo json_encode($message);
        } else {
            return $return_ ?? false;
        }
    }
}

function Delete($table, $where, $json = false)
{
    try {
        $CI = new db();
        $CI->db->table($table)->where($where)->delete();
        $return_ = $CI->db->affectedRows();
        if ($return_ == false) {
            throw new Exception('Gagal menghapus data');
        }
        $message = [
            'status' => 'ok',
            'message' => 'Berhasil menghapus data',
        ];
    } catch (\Throwable $th) {
        $message = [
            'status' => 'fail',
            'message' => $th->getMessage(),
        ];
    } catch (\Exception $ex) {
        $message = [
            'status' => 'fail',
            'message' => $ex->getMessage(),
        ];
    } finally {
        if ($json == true) {
            echo json_encode($message);
        } else {
            return $return_ ?? false;
        }
    }
}

/**
 * Validate.
 */
function Validate($data, $guarded = [])
{
    $validate = [
        'required' => 'harus diisi',
        'min' => 'harus diisi setidaknya $n karakter',
        'max' => 'tidak boleh diisi lebih dari $n karakter',
        'minNum' => 'nilai harus lebih besar atau sama dengan $n',
        'maxNum' => 'nilai harus kurang dari atau sama dengan $n',
        'number' => 'harus diisi oleh angka',
        'string' => 'harus diisi oleh huruf',
        'email' => 'harus diisi oleh Email yang benar',
        'name' => 'hanya boleh diisi oleh huruf dan spasi',
        'username' => 'hanya boleh diisi oleh huruf dan nomor',
        'password' => 'harus mengandung karakter, angka, simbol dan huruf besar',
        'sameAs' => 'tidak sama dengan $target',
        'date' => 'format tidak benar',
    ];
    $error = [];
    $errorCount = 0;
    foreach ($data as $key => $request) {
        $req = explode('|', $request);
        foreach ($req as $request_) {
            if ($request_ == 'required') {
                if (!isset($_REQUEST[$key]) || trim($_REQUEST[$key]) == '') {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => false,
                        'message' => str_replace(['$key', '_', '-'], [$key, ' ', ' '], $validate[$request_]),
                    ];
                    ++$errorCount;
                    break;
                } else {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => true,
                    ];
                }
            }
            if (substr($request_, 0, 3) == 'min') {
                $param = explode(':', $request_);
                if (!isset($_REQUEST[$key]) || strlen($_REQUEST[$key]) < $param[1]) {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => false,
                        'message' => str_replace(['$key', '$n'], [$key, $param[1]], $validate[$param[0]]),
                    ];
                    ++$errorCount;
                    break;
                } else {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => true,
                    ];
                }
            }
            if (substr($request_, 0, 3) == 'max') {
                $param = explode(':', $request_);
                if (!isset($_REQUEST[$key]) || strlen($_REQUEST[$key]) > $param[1]) {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => false,
                        'message' => str_replace(['$key', '$n'], [$key, $param[1]], $validate[$param[0]]),
                    ];
                    ++$errorCount;
                    break;
                } else {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => true,
                    ];
                }
            }
            if (substr($request_, 0, 6) == 'minNum') {
                $param = explode(':', $request_);
                // echo $_REQUEST[$key];
                if (!isset($_REQUEST[$key]) || $_REQUEST[$key] < $param[1]) {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => false,
                        'message' => str_replace(['$key', '$n'], [$key, $param[1]], $validate[$param[0]]),
                    ];
                    ++$errorCount;
                    break;
                } else {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => true,
                    ];
                }
            }
            if (substr($request_, 0, 6) == 'maxNum') {
                $param = explode(':', $request_);
                // echo $_REQUEST[$key];
                if (!isset($_REQUEST[$key]) || $_REQUEST[$key] > $param[1]) {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => false,
                        'message' => str_replace(['$key', '$n'], [$key, $param[1]], $validate[$param[0]]),
                    ];
                    ++$errorCount;
                    break;
                } else {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => true,
                    ];
                }
            }
            if ($request_ == 'string') {
                if (preg_match('/[^A-Za-z ]/', $_REQUEST[$key])) {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => false,
                        'message' => str_replace(['$key'], $key, $validate[$request_]),
                    ];
                    ++$errorCount;
                    break;
                } else {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => true,
                    ];
                }
            }
            if ($request_ == 'number') {
                if (preg_match('/[^0-9-]/', $_REQUEST[$key])) {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => false,
                        'message' => str_replace(['$key'], $key, $validate[$request_]),
                    ];
                    ++$errorCount;
                    break;
                } else {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => true,
                    ];
                }
            }
            if ($request_ == 'name') {
                if (!preg_match("/^[a-zA-Z-' ]*$/", $_REQUEST[$key])) {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => false,
                        'message' => str_replace(['$key'], $key, $validate[$request_]),
                    ];
                    ++$errorCount;
                    break;
                } else {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => true,
                    ];
                }
            }
            if ($request_ == 'username') {
                if (!preg_match("/^[a-zA-Z-0-9']*$/", $_REQUEST[$key])) {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => false,
                        'message' => str_replace(['$key'], $key, $validate[$request_]),
                    ];
                    ++$errorCount;
                    break;
                } else {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => true,
                    ];
                }
            }
            if ($request_ == 'email') {
                if (!filter_var($_REQUEST[$key], FILTER_VALIDATE_EMAIL)) {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => false,
                        'message' => str_replace('$key', $key, $validate[$request_]),
                    ];
                    ++$errorCount;
                    break;
                } else {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => true,
                    ];
                }
            }
            if ($request_ == 'password') {
                if (
                    !preg_match('/[\'^£$%&*()}{@#~?!><>,|=_+¬-]/', $_REQUEST[$key])
                    || !preg_match('/[0-9 ]/', $_REQUEST[$key])
                    || !preg_match('/[A-Z ]/', $_REQUEST[$key])
                ) {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => false,
                        'message' => str_replace('$key', $key, $validate[$request_]),
                    ];
                    ++$errorCount;
                    break;
                } else {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => true,
                    ];
                }
            }
            if (substr($request_, 0, 6) == 'sameAs') {
                $param = explode(':', $request_);
                if (!isset($_REQUEST[$key]) || $_REQUEST[$key] != $_REQUEST[$param[1]]) {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => false,
                        'message' => str_replace(['$key', '$target'], [$key, $param[1]], $validate[$param[0]]),
                    ];
                    ++$errorCount;
                    break;
                } else {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => true,
                    ];
                }
            }
            if ($request_ == 'date') {
                $d = \DateTime::createFromFormat('Y-m-d', $_REQUEST[$key]);
                $cek = $d && $d->format('Y-m-d') === $_REQUEST[$key];
                if (!$cek) {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => false,
                        'message' => str_replace(['$key'], [$key], $validate[$request_]),
                    ];
                    ++$errorCount;
                    break;
                } else {
                    $error[$key] = [
                        'input' => $key,
                        'type' => $request_,
                        'valid' => true,
                    ];
                }
            }
        }
    }
    if ($errorCount > 0) {
        return [
            'input' => $error,
            'success' => false,
        ];
    } else {
        $setField = array_merge($data, $guarded);
        $request = [];
        foreach ($setField as $key => $value) {
            $request[$key] = Input_($key);
        }
        if ($guarded != null) {
            foreach ($guarded as $guard_ => $value) {
                if ($value == false) {
                    unset($request[$guard_]);
                } else {
                    unset($request[$guard_]);
                    $request[$guard_] = $value;
                }
            }
        }

        return [
            'success' => true,
            'input' => $error,
            'data' => $request,
        ];
    }
}

function getBetweenDates($startDate, $endDate, $ignoreWeekDays = false)
{
    $rangArray = [];

    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    for (
        $currentDate = $startDate;
        $currentDate <= $endDate;
        $currentDate += (86400)
    ) {
        if ($ignoreWeekDays == true) {
            $date = date('Y-m-d', $currentDate);
            $dateName = strtolower(date("D", $currentDate));
            if (!in_array($dateName, ['sat', 'sun'])) $rangArray[] = $date;
        } else {
            $date = date('Y-m-d', $currentDate);
            $rangArray[] = $date;
        }
    }

    return $rangArray;
}

function Enc($text)
{
    return md5(sha1(md5(sha1(sha1(md5(md5(sha1(md5(md5($text))))))))));
}

function EncKey($key)
{
    return "md5(sha1(md5(sha1(sha1(md5(md5(sha1(md5(md5($key))))))))))";
}

function ValidateAdd($validate, $input, $message)
{
    $validate['success'] = false;
    $valid = $validate['input'];
    $valid[$input] = ['input' => $input, 'message' => $message, 'valid' => false];
    $validate['input'] = $valid;

    return $validate;
}

function Guard($data, $guard)
{
    $result = $data;
    foreach ($guard as $protect) {
        $param = explode(':', $protect);
        if (count($param) > 1) {
            if ($param[1] == 'hash') {
                $result[$param[0]] = Enc($result[$param[0]]);
            }
        } else {
            unset($result[$protect]);
        }
    }

    return $result;
}

function Input_($input = '', $escape = false)
{
    $CI = new db();

    return $escape == false ? trim(rtrim($_REQUEST[$input] ?? '')) : ltrim(rtrim($CI->db->escapeString($_REQUEST[$input] ?? '')));
}

function isMultiArray($a)
{
    $rv = array_filter($a, 'is_array');
    if (count($rv) > 0) {
        return true;
    }

    return false;
}

function Print_($array, $clear = true, $stop = true)
{
    if ($clear == true) {
        ob_clean();
        echo '<pre>';
        echo print_r($array);
        echo '</pre>';
        exit(0);
    } else {
        echo '<pre>';
        echo print_r($array);
        echo '</pre>';
        if ($stop == true) {
            exit(0);
        }
    }
}

function slug($string)
{
    return preg_replace('/[^a-zA-Z0-9 -]/', '', str_replace([' ', '.', ','], '-', strtolower($string)));
}

function Render($view)
{
    return preg_replace('!\s+!', ' ', $view);
}

function base64Enc($text, $times = 1)
{
    if ($times == 1) {
        return base64_encode($text);
    }
    for ($i = 0; $i < $times; ++$i) {
        $text = base64_encode($text);
    }

    return $text;
}

function base64Dec($text, $times = 1)
{
    if ($times == 1) {
        return base64_decode($text);
    }
    for ($i = 0; $i < $times; ++$i) {
        $text = base64_decode($text);
    }

    return $text;
}

function InputTags($input)
{
    return htmlspecialchars(trim(rtrim($_REQUEST[$input])));
}

function isPost()
{
    return $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false;
}