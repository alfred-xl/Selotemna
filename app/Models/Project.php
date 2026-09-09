<?php

namespace App\Models;

use App\Enums\ProjectDivision;
use App\Enums\ProjectPublicationStatus;
use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name',
    'slug',
    'division',
    'project_type',
    'status',
    'publication_status',
    'is_featured',
    'is_publication_verified',
    'summary',
    'overview',
    'marketing_summary',
    'location_summary',
    'land_title',
    'title_information',
    'price_per_sqm',
    'currency',
    'allocation_details',
    'construction_details',
    'disclaimer',
    'canonical_path',
    'seo_title',
    'seo_description',
    'published_at',
    'sort_order',
])]
class Project extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'division' => ProjectDivision::class,
            'status' => ProjectStatus::class,
            'publication_status' => ProjectPublicationStatus::class,
            'is_featured' => 'boolean',
            'is_publication_verified' => 'boolean',
            'price_per_sqm' => 'integer',
            'published_at' => 'datetime',
            'sort_order' => 'integer',
        ];
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->status->label());
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('publication_status', ProjectPublicationStatus::Published)
            ->where('is_publication_verified', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /** @return array<int, string> */
    public function publicationIssues(): array
    {
        $issues = collect([
            blank($this->name) ? 'Enter the project name.' : null,
            blank($this->slug) ? 'Enter the public URL slug.' : null,
            blank($this->project_type) ? 'Enter the project type.' : null,
            blank($this->summary) ? 'Enter the project-card summary.' : null,
            blank($this->overview) ? 'Enter the public project overview.' : null,
            blank($this->location_summary) && ! $this->locations()->exists() ? 'Add a project location.' : null,
            blank($this->canonical_path) ? 'Enter the canonical public path.' : null,
            blank($this->seo_title) ? 'Enter the SEO title.' : null,
            blank($this->seo_description) ? 'Enter the SEO description.' : null,
            blank($this->published_at) ? 'Choose a publication date.' : null,
            ! $this->is_publication_verified ? 'Confirm that the project information is verified and approved for publication.' : null,
            ! $this->media()->where(function (Builder $query): void {
                $query->whereNotNull('path')->orWhereNotNull('external_url');
            })->exists() ? 'Add at least one project image, poster, or video.' : null,
        ]);

        if ($this->division === ProjectDivision::RealEstateDevelopment) {
            $issues->push(
                blank($this->land_title) ? 'Enter the verified land title.' : null,
                blank($this->title_information) ? 'Enter the verified title information.' : null,
                ! is_numeric($this->price_per_sqm) || $this->price_per_sqm <= 0 ? 'Enter a valid price per sqm.' : null,
                ! $this->plotOptions()->exists() ? 'Add at least one plot option.' : null,
                blank($this->disclaimer) ? 'Enter the availability and pricing disclaimer.' : null,
            );
        }

        return $issues->filter()->values()->all();
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeForStatus(Builder $query, ProjectStatus|string $status): Builder
    {
        return $query->where('status', $status instanceof ProjectStatus ? $status : ProjectStatus::from($status));
    }

    public function scopeForDivision(Builder $query, ProjectDivision|string $division): Builder
    {
        return $query->where('division', $division instanceof ProjectDivision ? $division : ProjectDivision::from($division));
    }

    public function locations(): HasMany
    {
        return $this->hasMany(ProjectLocation::class)->orderBy('sort_order');
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProjectMedia::class)->orderBy('sort_order');
    }

    public function plotOptions(): HasMany
    {
        return $this->hasMany(ProjectPlotOption::class)->orderBy('sort_order');
    }

    public function paymentPlan(): HasOne
    {
        return $this->hasOne(ProjectPaymentPlan::class);
    }

    public function charges(): HasMany
    {
        return $this->hasMany(ProjectCharge::class)->orderBy('sort_order');
    }

    public function documentStages(): HasMany
    {
        return $this->hasMany(ProjectDocumentStage::class)->orderBy('sort_order');
    }

    public function infrastructure(): HasMany
    {
        return $this->hasMany(ProjectInfrastructure::class)->orderBy('sort_order');
    }

    public function policies(): HasMany
    {
        return $this->hasMany(ProjectPolicy::class)->orderBy('sort_order');
    }

    public function faqGroups(): HasMany
    {
        return $this->hasMany(ProjectFaqGroup::class)->orderBy('sort_order');
    }
}
