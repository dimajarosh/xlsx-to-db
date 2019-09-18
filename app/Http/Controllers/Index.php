<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use SimpleXLSX;

class Index extends Controller
{
    //
    public function index(Request $request) {
        ini_set('max_execution_time', 10);
        $request->validate([
            'lol' => ['required', 'mimes:xlsx', function ($attribute, $value) {
              if($value->getSize()/1024/1024 < ini_get("post_max_size")) {
                    return true;
                } else {
                    return false;
                }
            }, function ($attribute, $value) {
                if($value->extension() == 'xlsx') {
                    return true;
                } else {
                    return false;
                }

            }]
        ]);

        if ( $xlsx = SimpleXLSX::parse($request->file('lol')) ) {
            $data = $xlsx->rows();
            foreach ($data as $key => $value) {
                if ($data[$key][10] == "") {
                    array_splice($data[$key], 10, 1);
                } else {
                    array_splice($data[$key], 0,1 );
                }
            }
            $data = array_map("unserialize", array_unique(array_map("serialize", $data)));
            array_splice($data, 0,1);
            foreach ($data as $row) {
                $item = new Item();
                $item->heading_1 = $row[0];
                $item->heading_2 = $row[1];
                $item->category = $row[2];
                $item->brand = $row[3];
                $item->name = $row[4];
                $item->article = $row[5];
                $item->description = $row[6];
                $item->price = $row[7];
                $item->guarantee = $row[8];
                $item->accessibility = $row[9];
                $item->save();
            }
            $count = count($data);
            echo "<br>";

        } else {
            echo SimpleXLSX::parseError();
        }

        return view('response', ['count' => $count]);
    }
}
