<?php

namespace App\Console\Commands;

use App\Models\Record;
use Symfony\Component\Console\Output\ConsoleOutput;

class PersistRecord
{    
    /** @var  ConsoleOutput */
    protected static $output;

    /**
     * Create a new PersistRecord instance.
     *
     * @return void
     */
    public function __construct()
    {
        self::$output = new ConsoleOutput();
    }

    public function saveRecord($_source_id, $_data_type, $_data): ? Record
    {
        //_source_id is passed seperately as it is retrieved differently | event vs tracked_entity_instance
        $_record =  new Record([
            'record_id' => $_source_id,
            'data_source' => 'MOH_DHIS2_COVAX',
            'data_type' => $_data_type,
            'hash' => sha1(json_encode($_data)),
            'data' => json_encode($_data)
        ]);

        $_record -> save();

        return $_record;
    }

    public function updateRecord($_old_record, $_updated_data): ? Record
    {
        //only 'hash' and 'data' fields are to be updated, unless otherwise.
        $_old_record->hash = sha1(json_encode($_updated_data));
        $_old_record->data = json_encode($_updated_data);

        $_old_record->update();

        return $_old_record;
    }
}