<?php

namespace App\Http\Controllers;

use App\Item;
use DB;
use Illuminate\Http\Request;
use SimpleXLSX;

class XlsxController extends Controller
{
    public function saveXlsxToDb(Request $request) {
            ini_set('max_execution_time', 100);
            $request->validate([
                'file' => ['required', 'mimes:xlsx', function ($attribute, $value) {
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
            echo time()."<br>";
            if ( $xlsx = SimpleXLSX::parse($request->file('file')) ) {
                $data = $xlsx->rows();
                unset($xlsx);
                echo time();
                echo "<br>";
                foreach ($data as $key => $value) {
                    if ($data[$key][10] == "") {
                        array_splice($data[$key], 10, 1);
                    } else {
                        array_splice($data[$key], 0,1 );
                    }
                }
                echo time()."<br>";
                $data = array_map("unserialize", array_unique(array_map("serialize", $data)));
                array_splice($data, 0,1);
                $count = 0;
                echo time()."<br>";
                $insert_data = [];
                foreach ($data as $row) {
                    if (DB::table('items')->select(DB::raw('*'))->where('article', $row[5])->get()->count() == 0) {
//                    if (Item::where('article', $row[5])->count() == 0) {
                        array_push($insert_data, [
                            'heading_1' => $row[0],
                            'heading_2' => $row[1],
                            'category' => $row[2],
                            'brand' => $row[3],
                            'name' => $row[4],
                            'article' => $row[5],
                            'description' => $row[6],
                            'price' => $row[7],
                            'guarantee' => $row[8],
                            'accessibility' => $row[9]
                        ]);
                        $count++;

//                    Item::firstOrCreate([
//                        'heading_1' => $row[0],
//                        'heading_2' => $row[1],
//                        'category' => $row[2],
//                        'brand' => $row[3],
//                        'name' => $row[4],
//                        'article' => $row[5],
//                        'description' => $row[6],
//                        'price' => $row[7],
//                        'guarantee' => $row[8],
//                        'accessibility' => $row[9]
//                    ]);
//                        $item = new Item();
//                        $item->heading_1 = $row[0];
//                        $item->heading_2 = $row[1];
//                        $item->category = $row[2];
//                        $item->brand = $row[3];
//                        $item->name = $row[4];
//                        $item->article = $row[5];
//                        $item->description = $row[6];
//                        $item->price = $row[7];
//                        $item->guarantee = $row[8];
//                        $item->accessibility = $row[9];
//                        $item->save();
//                        $count++;
                    }
                }
                DB::table('items')->insert($insert_data);
                echo time()."<br>";

            } else {
                echo SimpleXLSX::parseError();
            }
//            var_dump(Item::where('article',$data[0][5])->count());
            return view('response', ['count' => $count]);
        }
}
