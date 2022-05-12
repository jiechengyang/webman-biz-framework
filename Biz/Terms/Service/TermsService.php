<?php

namespace Biz\Terms\Service;

interface TermsService
{
    public function getTermsById($id);

    public function getTermsInIds($ids);

    public function createTerms(array $fields);

    public function updateTerms($id, array $fields);

    public function deleteTermsById($id);

    public function batchCreateTerms(array $items);

    public function truncate();

    public function countTerms(array $conditions = []);

    public function searchTerms(array $conditions = [], array $orderby = [], $start, $limit, array $columns = []);

}
