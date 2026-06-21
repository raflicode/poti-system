<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            DB::table('users')->where('role', 'cashier')->update(['role' => 'piket']);

            if (Schema::hasColumn('users', 'active')) {
                Schema::table('users', fn (Blueprint $table) => $table->dropColumn('active'));
            }
        }

        if (Schema::hasTable('products')) {
            if (Schema::hasColumn('products', 'sku')) {
                $this->dropIndexIfExists('products', 'products_sku_unique');
                Schema::table('products', fn (Blueprint $table) => $table->dropColumn('sku'));
            }

            if (Schema::hasColumn('products', 'slug')) {
                $this->dropIndexIfExists('products', 'products_slug_unique');
                Schema::table('products', fn (Blueprint $table) => $table->dropColumn('slug'));
            }

            if (Schema::hasColumn('products', 'description')) {
                Schema::table('products', fn (Blueprint $table) => $table->dropColumn('description'));
            }

            DB::table('products')->where('stock', '<=', 0)->update(['status' => 'out_of_stock']);
            DB::table('products')->where('status', 'inactive')->update(['status' => 'out_of_stock']);
        }

        if (Schema::hasTable('transactions')) {
            if (Schema::hasColumn('transactions', 'cash_tendered') && ! Schema::hasColumn('transactions', 'paid_amount')) {
                Schema::table('transactions', fn (Blueprint $table) => $table->renameColumn('cash_tendered', 'paid_amount'));
            }

            if (Schema::hasColumn('transactions', 'items_count')) {
                Schema::table('transactions', fn (Blueprint $table) => $table->dropColumn('items_count'));
            }

            if (Schema::hasColumn('transactions', 'status')) {
                Schema::table('transactions', fn (Blueprint $table) => $table->dropColumn('status'));
            }
        }

        if (Schema::hasTable('transaction_items')) {
            if (Schema::hasColumn('transaction_items', 'unit_price') && ! Schema::hasColumn('transaction_items', 'price')) {
                Schema::table('transaction_items', fn (Blueprint $table) => $table->renameColumn('unit_price', 'price'));
            }

            if (Schema::hasColumn('transaction_items', 'line_total') && ! Schema::hasColumn('transaction_items', 'subtotal')) {
                Schema::table('transaction_items', fn (Blueprint $table) => $table->renameColumn('line_total', 'subtotal'));
            }
        }

        if (Schema::hasTable('schedules')) {
            if (Schema::hasColumn('schedules', 'status')) {
                Schema::table('schedules', fn (Blueprint $table) => $table->dropColumn('status'));
            }

            if (Schema::hasColumn('schedules', 'check_in_at')) {
                Schema::table('schedules', fn (Blueprint $table) => $table->dropColumn('check_in_at'));
            }

            if (Schema::hasColumn('schedules', 'notes')) {
                Schema::table('schedules', fn (Blueprint $table) => $table->dropColumn('notes'));
            }
        }
    }

    public function down(): void
    {
        //
    }

    private function dropIndexIfExists(string $table, string $index): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement("drop index if exists {$index}");

            return;
        }

        Schema::table($table, function (Blueprint $table) use ($index) {
            $table->dropIndex($index);
        });
    }
};
