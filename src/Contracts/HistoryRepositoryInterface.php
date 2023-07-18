<?php

namespace Adminetic\Website\Contracts;

use Adminetic\Website\Models\Admin\History;
use Adminetic\Website\Http\Requests\HistoryRequest;

interface HistoryRepositoryInterface
{
    public function indexHistory();

    public function createHistory();

    public function storeHistory(HistoryRequest $request);

    public function showHistory(History $History);

    public function editHistory(History $History);

    public function updateHistory(HistoryRequest $request, History $History);

    public function destroyHistory(History $History);
}
