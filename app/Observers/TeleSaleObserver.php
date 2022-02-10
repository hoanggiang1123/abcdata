<?php

namespace App\Observers;

use App\Models\TeleSale;

use App\Models\TeleSaleLog;

class TeleSaleObserver
{
    /**
     * Handle the TeleSale "created" event.
     *
     * @param  \App\Models\TeleSale  $teleSale
     * @return void
     */
    public function created(TeleSale $teleSale)
    {
        $old = $new = [];

        $old['created_by'] = auth()->user()->name;
        $new['created_by'] = auth()->user()->name;
        $oldString = count($old) > 0 ? json_encode($old) : '';
        $newString = count($new) > 0 ? json_encode($new) : '';

        if ($oldString !== '' && $newString !== '') {
            TeleSaleLog::create([
                'old_values' => $oldString,
                'new_values' => $newString,
                'tele_sale_id' => $teleSale->id,
                'updated_by_id' => auth()->user()->id
            ]);
        }
    }


    public function updating(TeleSale $teleSale)
    {
        $old = $new = [];

        $dirtyFields = [
            'full_name', 'phone', 'email', 'dob', 'status', 'username', 'line_id', 'agent', 'note', 'call_date', 'category_id', 'source', 'agent_id', 'line_id', 'vip_id', 'assign_id'
        ];

        foreach($dirtyFields as $value) {

            if ($teleSale->isDirty($value)) {

                $new[$value] = $teleSale->{$value};

                $old[$value] = $teleSale->getOriginal($value);
            }
        }
        $oldString = count($old) > 0 ? json_encode($old) : '';
        $newString = count($new) > 0 ? json_encode($new) : '';

        if ($oldString !== '' && $newString !== '') {
            TeleSaleLog::create([
                'old_values' => $oldString,
                'new_values' => $newString,
                'tele_sale_id' => $teleSale->id,
                'updated_by_id' => auth()->user()->id
            ]);
        }

    }

    /**
     * Handle the TeleSale "updated" event.
     *
     * @param  \App\Models\TeleSale  $teleSale
     * @return void
     */
    public function updated(TeleSale $teleSale)
    {
        //
    }

    /**
     * Handle the TeleSale "deleted" event.
     *
     * @param  \App\Models\TeleSale  $teleSale
     * @return void
     */
    public function deleted(TeleSale $teleSale)
    {
        //
    }

    /**
     * Handle the TeleSale "restored" event.
     *
     * @param  \App\Models\TeleSale  $teleSale
     * @return void
     */
    public function restored(TeleSale $teleSale)
    {
        //
    }

    /**
     * Handle the TeleSale "force deleted" event.
     *
     * @param  \App\Models\TeleSale  $teleSale
     * @return void
     */
    public function forceDeleted(TeleSale $teleSale)
    {
        //
    }
}
