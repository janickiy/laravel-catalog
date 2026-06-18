<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCatalogAndLinksTables extends Migration
{
    public function up(): void
    {
        $this->changeReferenceColumns(nullable: true);
        $this->normalizeCatalogReferences();

        Schema::table('catalog', function (Blueprint $table) {
            $table->foreign('parent_id', 'catalog_parent_id_foreign')
                ->references('id')
                ->on('catalog')
                ->cascadeOnDelete();
        });

        Schema::table('links', function (Blueprint $table) {
            $table->foreign('catalog_id', 'links_catalog_id_foreign')
                ->references('id')
                ->on('catalog')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropForeign('links_catalog_id_foreign');
        });

        Schema::table('catalog', function (Blueprint $table) {
            $table->dropForeign('catalog_parent_id_foreign');
        });

        DB::table('links')->whereNull('catalog_id')->update(['catalog_id' => 0]);
        DB::table('catalog')->whereNull('parent_id')->update(['parent_id' => 0]);

        $this->changeReferenceColumns(nullable: false);
    }

    private function changeReferenceColumns(bool $nullable): void
    {
        $bigInteger = $this->catalogIdIsBigInteger();

        Schema::table('catalog', function (Blueprint $table) use ($bigInteger, $nullable) {
            $this->changeReferenceColumn($table, 'parent_id', $bigInteger, $nullable);
        });

        Schema::table('links', function (Blueprint $table) use ($bigInteger, $nullable) {
            $this->changeReferenceColumn($table, 'catalog_id', $bigInteger, $nullable);
        });
    }

    private function changeReferenceColumn(Blueprint $table, string $name, bool $bigInteger, bool $nullable): void
    {
        $column = $bigInteger
            ? $table->unsignedBigInteger($name)
            : $table->unsignedInteger($name);

        $nullable
            ? $column->nullable()->default(null)
            : $column->default(0);

        $column->change();
    }

    private function catalogIdIsBigInteger(): bool
    {
        $column = DB::selectOne("SHOW COLUMNS FROM catalog WHERE Field = 'id'");

        return isset($column->Type) && str_contains(strtolower($column->Type), 'bigint');
    }

    private function normalizeCatalogReferences(): void
    {
        $catalogIds = DB::table('catalog')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $this->normalizeCatalogParents($catalogIds);
        $this->normalizeLinkCatalogs($catalogIds);
    }

    private function normalizeCatalogParents(array $catalogIds): void
    {
        $query = DB::table('catalog')->where('parent_id', 0);

        if ($catalogIds === []) {
            $query->orWhereNotNull('parent_id');
        } else {
            $query->orWhereNotIn('parent_id', $catalogIds);
        }

        $query->update(['parent_id' => null]);
    }

    private function normalizeLinkCatalogs(array $catalogIds): void
    {
        $query = DB::table('links')->where('catalog_id', 0);

        if ($catalogIds === []) {
            $query->orWhereNotNull('catalog_id');
        } else {
            $query->orWhereNotIn('catalog_id', $catalogIds);
        }

        $query->update(['catalog_id' => null]);
    }
}
