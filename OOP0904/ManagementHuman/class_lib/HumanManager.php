<?php


class HumanManager
{
    protected $listHuman = [];
    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function addHuman($human)
    { // tham chiếu đến đối tượng human khi nhập vào để lấy các thuộc tính của nó
        $data = [ // tao mang data khi ng dung nhap vao form tu doi tuong human
            'name' => $human->getName(),
            'age' => $human->getAge(),
            'address' => $human->getAddress()
        ];
        $humanData = $this->getJsonData(); // tạo mảng hứng dâta json sau khi chuyển
        array_push($humanData, $data); // chuyển data vào mang json
        $this->saveJsonData($humanData); // chuyển mảng đã push dâta về dạng json để lưu trữ
    }


    public function listHuman()
    { // tạo ra list human từ json
        $humanJson = $this->getJsonData(); // hứng data json
        foreach ($humanJson as $obj) { // duyệt từng phần từ mảng trên vào list
            $human = new Human($obj->name, $obj->age, $obj->address);
            array_push($this->listHuman, $human);
        }
        return $this->listHuman;
    }

    public function getJsonData()
    { // chuyen json thanh mang
        $dataJson = file_get_contents($this->filePath);
        return json_decode($dataJson);
    }

    public function saveJsonData($data)
    { // sau khi push vao mang thi chuyen tra ve dang json de luu
        $dataJson = json_encode($data);
        return file_put_contents($this->filePath, $dataJson);
    }

    public function getHumanByIndex($index)
    {
        $data = $this->getJsonData(); //lấy json vào chuyển thành m
        $obj = $data[$index];         // phần tử thứ index
        return new Human($obj->name, $obj->age, $obj->address);
    }

    public function updateHuman($index, $human)
    { //truyen vao vi tri va data doi tuong
        $data = $this->getJsonData();            // chuyeenr json thanh mang
        $arr = [                                    // data mới lấy từ data có index ra để chỉnh s
            'name' => $human->getName(),
            'age' => $human->getAge(),
            'address' => $human->getAddress()
        ];
        $data[$index] = $arr;
        $this->saveJsonData($data);
    }

    public function deleteHuman($index)
    {
        $data = $this->getJsonData(); // chuyen json thanh mang
        //unset($data[$index]);
        array_splice($data, $index, 1); // xoa 1 phan tu index
        $this->saveJsonData($data); // encode lai ve json
    }

    public function searchHuman($keyWord)
    {
        $dataJson = $this->getJsonData();
        $arr = [];
        foreach ($dataJson as $obj) {
            if (strrpos($obj->name, $keyWord) !== false) {
                array_push($arr, $obj);
            }
        }
        return $arr;

    }

    public function getIndex($keyWord){
        $dataJson = $this->getJsonData();
        foreach ($dataJson as $key) {
            if ($key->name == $keyWord) {
                return $key;
            }
        }
    }
}