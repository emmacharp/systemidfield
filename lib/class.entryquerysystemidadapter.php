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
    public function getFilterColumns()
    {
        return ['id'];
    }

    public function filter(EntryQuery $query, array $filters, $operator = 'or')
    {
        General::ensureType([
            'operator' => ['var' => $operator, 'type' => 'string'],
        ]);
        if (empty($filters)) {
            return;
        }
        $field_id = General::intval($this->field->get('id'));
        $conditions = [];
        // `not:` needs a special treatment because 'and' operation are using the 'or' notation (,)
        // In order to make the filter work, we must change 'or' to 'and'
        // and propagate the prefix to all other un-prefix $filters
        if ($operator === 'or' && preg_match('/not[^\:]*:/i', $filters[0])) {
            $operator = 'and';
            $prefix = current(explode(':', $filters[0], 2));
            foreach ($filters as $i => $filter) {
                if ($i === 0) {
                    continue;
                }
                if (strpos($filter, ':') === false) {
                    $filters[$i] = "$prefix: $filter";
                } else {
                    break;
                }
            }
        }

        foreach ($filters as $filter) {
            $fc = $this->filterSingle($query, $filter);
            if (is_array($fc)) {
                $conditions[] = $fc;
            }
        }
        if (empty($conditions)) {
            return;
        }
        // Prevent adding unnecessary () when dealing with a single condition
        if (count($conditions) > 1) {
            $conditions = [$operator => $conditions];
        }
        $query->where($conditions);
    }

    public function getSortColumns()
    {
        return ['id'];
    }

    public function sort(EntryQuery $query, $direction = 'ASC')
    {
        General::ensureType([
            'direction' => ['var' => $direction, 'type' => 'string'],
        ]);
        if ($this->isRandomOrder($direction)) {
            $query->orderBy('RAND()');
            return;
        }
        foreach ($this->getSortColumns() as $col) {
            $query->orderBy($col, $direction);
        }
    }
}
