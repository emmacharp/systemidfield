<?php

/**
 * @package toolkit
 */
/**
 * Specialized EntryQueryFieldAdapter that facilitate creation of queries filtering/sorting data from
 * an textarea Field.
 * @see FieldSystemId
 * @since Symphony 3.0.0
 */
class EntryQuerySystemIdAdapter extends EntryQueryFieldAdapter
{
    public function getSortColumns()
    {
        return ['id'];
    }
}
