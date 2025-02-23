<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class Model extends EloquentModel
{
    use HasFactory;

    public static $perpage = 25;

    public $timestamps = true;

    protected $primaryKey = 'id';

    protected static function getColumns(string $pTable): array
    {
        $fetchRows = self::query()
        ->select('column_name')
        ->from('information_schema.columns')
        ->where('table_name', $pTable)
        ->get();

        if (! $fetchRows) {
            return [];
        }

        $columns = [];
        foreach ($fetchRows->toArray() as $fetchRow) {
            $columns[] = $fetchRow['column_name'];
        }

        return array_map('strtolower', $columns);
    }

    public function scopeFilters(Builder $pQuery, array $pFilters = [], array $pLiked = []): void
    {
        foreach ($pFilters as $key => $value) {
            if ($key === 'initial') {
                $signalCompare = '>=';
                $key = 'created_at';
            } elseif ($key === 'final') {
                $signalCompare = '<=';
                $key = 'created_at';
            } elseif (in_array($key, $pLiked)) {
                $signalCompare = 'LIKE';
                $value = "%{$value}%";
            } else {
                $signalCompare = '=';
            }

            $table = (str_contains($key, '.')) ? $key : ((isset($this->table) && ! empty($this->table)) ? "{$this->table}.{$key}" : $key);
            $pQuery->where($table, $signalCompare, $value);
        }
    }

    public static function getAll($pColumns = ['*']): Collection
    {
        return self::all($pColumns);
    }

    public static function getById(int $pId)
    {
        return self::find($pId);
    }

    public static function filtered(array $pFilters, string $pOrderBy = null, $pDirection = 'ASC'): LengthAwarePaginator
    {
        $currentPage = request('page', 1);
        $query = self::query()->filters($pFilters);
        if ($pOrderBy) {
            $query->orderBy($pOrderBy, $pDirection);
        }

        $fetchRows = $query->get();

        $paginated = new LengthAwarePaginator(
            $fetchRows->forPage($currentPage, self::$perpage),
            $fetchRows->count(),
            self::$perpage,
            $currentPage
        );

        return $paginated;
    }

    public static function paginationByFilters(array $pFilters, string $pOrderBy = null, $pDirection = 'ASC')
    {
        $currentPage = request('page', 1);
        $query = self::query()->filters($pFilters);
        if ($pOrderBy) {
            $query->orderBy($pOrderBy, $pDirection);
        }

        $fetchRows = $query->get();

        $paginated = new LengthAwarePaginator(
            $fetchRows->forPage($currentPage, self::$perpage),
            $fetchRows->count(),
            self::$perpage,
            $currentPage
        );

        return $paginated;
    }

    public static function firstByFilters(array $pFilters): self|null
    {
        $fetchRow = self::query()
            ->filters($pFilters)
            ->first();

        return $fetchRow;
    }

    public static function isExists(int $pId): bool
    {
        return self::getById($pId) ? true : false;
    }

    public static function showWith(int $id, array $relations = []): ?Model
    {
        return self::with($relations)->find($id);
    }

    public static function updateWith(int $id, array $data, array $additionalConditions = []): ?bool
    {
        $query = self::where('id', $id);
        foreach ($additionalConditions as $key => $value) {
            $query->where($key, $value);
        }

        return $query->update($data);
    }
}
