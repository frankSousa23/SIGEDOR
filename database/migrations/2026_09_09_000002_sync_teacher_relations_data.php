<?php

use App\Models\Category;
use App\Models\Dedication;
use App\Models\Site;
use App\Models\Teacher;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Sincronizar categorías por CDI
        Category::query()->each(function (Category $category) {
            if ($category->teacher_cdi) {
                Teacher::where('cdi', $category->teacher_cdi)
                    ->where(function ($q) use ($category) {
                        $q->whereNull('category_id')
                            ->orWhere('category_id', '!=', $category->id);
                    })
                    ->update(['category_id' => $category->id]);
            }
        });

        // Sincronizar dedicaciones por CDI
        Dedication::query()->each(function (Dedication $dedication) {
            if ($dedication->teacher_cdi) {
                Teacher::where('cdi', $dedication->teacher_cdi)
                    ->where(function ($q) use ($dedication) {
                        $q->whereNull('dedication_id')
                            ->orWhere('dedication_id', '!=', $dedication->id);
                    })
                    ->update(['dedication_id' => $dedication->id]);
            }
        });

        // Sincronizar sites por CDI
        Site::query()->each(function (Site $site) {
            if ($site->teacher_cdi) {
                Teacher::where('cdi', $site->teacher_cdi)
                    ->where(function ($q) use ($site) {
                        $q->whereNull('site_id')
                            ->orWhere('site_id', '!=', $site->id);
                    })
                    ->update(['site_id' => $site->id]);
            }
        });
    }

    public function down(): void
    {
        // No destructivo
    }
};
