<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\LegalAdvice\Registry;
use App\Models\Admin\FileManager;

class LegalAdvicesRegistries extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];
    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run() {
        $this->files = FileManager::getFiles(null, 'LegalAdvice\RegistryController')->pluck('route_id', 'id')->countBy();

        $items = Registry::selectRaw("*, DATE_PART('day', deadline - timestamp '". date('Y-m-d') ."') AS remainingdays")
            ->selectRaw('(SELECT COUNT(id) FROM procedures WHERE registry_id = registries.id) AS procedures')
            ->orderBy('deadline', 'DESC')
            ->get();

        $items->each( function($item) {
            $item->files = isset($this->files[$item->id]) ? $this->files[$item->id] : 0;
        });

        $out = new \StdClass;
        $out->uptodate = [];
        $out->deadline = [];
        $out->late = [];

        if ($items) {
            foreach ($items as $item) {
                if ( $item->remainingdays >= 3 ) {
                    $out->uptodate[] = $item;
                } 
                elseif ( $item->remainingdays >= 0 && $item->remainingdays < 3 ) {
                    $out->deadline[] = $item;
                }
                else { //( $days = datediffdays($item->deadline) < 0 ) {
                    $out->late[] = $item;
                }
    
            }
        }
        
        return view('widgets.legal_advices_registries', [
            'items' => $out,
            'total' => count($items),
        ]);
    }
}
