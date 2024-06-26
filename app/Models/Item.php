<?php

namespace App\Models;

use Weaviate\Weaviate;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;


class Item extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'image_src',
        'asset_id',
        'tiny_placeholder',
        'year',
    ];

    public function assets()
    {
        return $this->hasMany(ItemAsset::class);
    }

    public function previewAsset()
    {
        return $this->hasOne(ItemAsset::class)->orderBy('name');
    }

    public function getDatingAttribute()
    {
        $separator = ' – ';
        $years_from = self::formatDatesIntoCollection($this->year_from);
        $years_to = self::formatDatesIntoCollection($this->year_to)->sort();
        $acquisition_dates = self::formatDatesIntoCollection($this->acquisition_date);
        $year_from = $years_from->first();
        $year_to = $years_to->first();
        if (Str::contains($year_from, 'Chr.')) {
            if (Str::contains($year_to, 'Chr.') && $year_from != $year_to && $year_to != ' v. Chr.') {
                return $year_from . $separator . $year_to;
            }
            return $year_from;
        }
        if (!empty($year_to)) {
            return $year_from . $separator . $year_to;
        } else {
            if ($year_from != $acquisition_dates->first) {
                return $years_from->first();
            }
        }
        return null;
    }

    public function getSimilar($limit = 1, $exclude = [])
    {
        $items = $this->whereNotNull('tiny_placeholder')->where('collection', 'LIKE', $this->collection)->where('id', '>', $this->id)->orderBy('id', 'asc')->whereNotIn('id', $exclude)->limit($limit)->get();
        if ($items->count() == 0) {
            $items = $this->whereNotNull('tiny_placeholder')->where('collection', 'LIKE', $this->collection)->where('id', '<', $this->id)->orderBy('id', 'desc')->whereNotIn('id', $exclude)->limit($limit)->get();
        }
        if ($items->count() == 0) {
            $items = $this->whereNotNull('tiny_placeholder')->whereNotIn('id', $exclude)->inRandomOrder()->limit($limit)->get();
        }
        return $items;
    }

    public function getVisualySimilar($limit = 1, $exclude = [])
    {
        $weaviate = new Weaviate(config('services.weaviate.url'), config('services.weaviate.token'));

        // get weaviate Object ID
        $data = $weaviate->graphql()->get('{
            Get {
              '.config('services.weaviate.index').' (
                limit: 1
                where: {
                  path: ["identifier"],
                  operator: Equal,
                  valueText: "'.$this->id.'"
                }
              ) {
                identifier
                _additional {
                  id
                  distance
                }
              }
            }
          }');
          
          
        $object_id= Arr::get($data, 'data.Get.Item.0._additional.id');

        $exclude_query = '';
        foreach ($exclude as $exclude_id) {
            $exclude_query .= '
            {
                path: ["identifier"],
                operator: NotEqual,
                valueText: "'.$exclude_id.'",
            },
            ';
        } 

        if (!empty($exclude_query)) {
            $exclude_query = '
            where: {
                operator: And,
                operands: [
                    '.$exclude_query.'
                    ]
                }
            ';
        }

        
        $data = $weaviate->graphql()->get('{
            Get {
              '.config('services.weaviate.index').' (
                offset: 1
                limit: '.$limit.'
                nearObject: {
                  id: "'.$object_id.'"
                }
                '.$exclude_query.'
              ) {
                identifier
                _additional {
                  distance
                }
              }
            }
          }');
        $ids = collect(Arr::pluck(Arr::get($data, 'data.Get.Item') ?? [], 'identifier'));
        
        // $filteredIds = $ids->reject(function ($id) use ($exclude) {
        //     return in_array($id, $exclude);
        // })->take($limit);


        $items = $this->whereNotNull('tiny_placeholder')->whereNotNull('year')->whereIn('id', $ids)->limit($limit)->get();
        
        // fallback... to return at lease better to remove later
        // if ($items->count() < $limit) {
        //     return $this->getSimilar($limit, $exclude);
        // }
        return $items;
    }

    public function getOlder($exclude = [])
    {
      $olderItem = self::whereNotNull('tiny_placeholder')->whereNotNull('year')->where('year', '<=', $this->year)->whereNotIn('id', $exclude)->inRandomOrder()->first();
      return $olderItem;
    }


    public function getYounger($exclude = [])
    {
      $youngerItem = Item::whereNotNull('tiny_placeholder')->whereNotNull('year')->where('year', '>=', $this->year)->whereNotIn('id', $exclude)->inRandomOrder()->first();
      return $youngerItem;
    }

    public function getDifferent($exclude = [])
    {
      $differentItem = Item::whereNotNull('tiny_placeholder')->whereNotNull('year')->where('year', '!=', $this->year)->whereNotIn('id', $exclude)->inRandomOrder()->first();
      return $differentItem;
    }


    public static function formatDatesIntoCollection($date_string, $separator = ' / ')
    {
        return collect(explode($separator, $date_string))->map(function ($year) {
            return  preg_replace('/^.*?(\d{4})$/', '$1', $year);
        });
    }

    function getImageRoute(int $size = 1354) {
        return 'https://mdo.mkg-hamburg.de/MDO/mediadelivery/rendition/' . $this->asset_id . '/-FJPG-B' . $size; // B1354 -> bounding box 1354px 
    }

    function getImagePreview() {
      if ($this->tiny_placeholder) {
        return route('image.show', ['id' => $this->id, 'size' => 'tiny']);
      }
      return null;
  }
}
