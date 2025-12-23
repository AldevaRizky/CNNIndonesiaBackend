@extends('layouts.dashboard')
@section('title', 'Dashboard Admin')
@section('content')

<div class="container-xxl py-4">
	<div class="row g-3 mb-4">
		<div class="col-md-3">
			<div class="card shadow-sm h-100">
				<div class="card-body d-flex align-items-center gap-3">
					<div class="bg-primary text-white rounded p-3">
						<i class="bi bi-file-earmark-text" style="font-size:24px"></i>
					</div>
					<div>
						<small class="text-muted">Total Articles</small>
						<h4 class="mb-0">{{ number_format($totalArticles) }}</h4>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-3">
			<div class="card shadow-sm h-100">
				<div class="card-body d-flex align-items-center gap-3">
					<div class="bg-success text-white rounded p-3">
						<i class="bi bi-eye" style="font-size:24px"></i>
					</div>
					<div>
						<small class="text-muted">Total Views</small>
						<h4 class="mb-0">{{ number_format($totalViews) }}</h4>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-3">
			<div class="card shadow-sm h-100">
				<div class="card-body d-flex align-items-center gap-3">
					<div class="bg-warning text-white rounded p-3">
						<i class="bi bi-journal-bookmark" style="font-size:24px"></i>
					</div>
					<div>
						<small class="text-muted">Published</small>
						<h4 class="mb-0">{{ number_format($publishedCount) }}</h4>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-3">
			<div class="card shadow-sm h-100">
				<div class="card-body d-flex align-items-center gap-3">
					<div class="bg-secondary text-white rounded p-3">
						<i class="bi bi-folder" style="font-size:24px"></i>
					</div>
					<div>
						<small class="text-muted">Categories</small>
						<h4 class="mb-0">{{ number_format($totalCategories) }}</h4>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3">
		<div class="col-lg-8">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h5 class="mb-0">Article Activity (Last 7 days)</h5>
					<small class="text-muted">Realtime</small>
				</div>
				<div class="card-body">
					<div id="articlesChart" style="height:320px;"></div>
				</div>
			</div>

			<div class="card mt-3">
				<div class="card-header">
					<h5 class="mb-0">Recent Articles</h5>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table mb-0 align-middle">
							<thead>
								<tr>
									<th>Title</th>
									<th>Category</th>
									<th>Status</th>
									<th>Views</th>
									<th>Created</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($recentArticles as $art)
								<tr>
									<td>{{ Str::limit($art->title, 70) }}</td>
									<td>{{ $art->category->name ?? '-' }}</td>
									<td><span class="badge bg-{{ $art->status == 'published' ? 'success' : 'secondary' }}">{{ ucfirst($art->status) }}</span></td>
									<td>{{ number_format($art->view_count) }}</td>
									<td>{{ $art->created_at->diffForHumans() }}</td>
									<td class="text-end">
										<a href="{{ route('admin.articles.show', $art->id) }}" class="btn btn-sm btn-info">View</a>
										<a href="{{ route('admin.articles.edit', $art->id) }}" class="btn btn-sm btn-warning">Edit</a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="card mb-3">
				<div class="card-header">
					<h5 class="mb-0">Status Breakdown</h5>
				</div>
				<div class="card-body">
					<div id="statusDonut" style="height:240px"></div>
					<div class="mt-3 d-flex justify-content-between">
						<div><small class="text-muted">Published</small><div class="fw-bold">{{ $publishedCount }}</div></div>
						<div><small class="text-muted">Draft</small><div class="fw-bold">{{ $draftCount }}</div></div>
					</div>
				</div>
			</div>

			<div class="card">
				<div class="card-header">
					<h5 class="mb-0">Recent Categories</h5>
				</div>
				<div class="card-body">
					<ul class="list-group list-group-flush">
						@foreach($recentCategories as $cat)
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<div>
									<strong>{{ $cat->name }}</strong>
									<div class="small text-muted">{{ $cat->created_at->diffForHumans() }}</div>
								</div>
								<div>
									<a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
								</div>
							</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
	// Articles chart
	var options = {
		chart: { type: 'area', height: 320, toolbar: { show: false } },
		dataLabels: { enabled: false },
		stroke: { curve: 'smooth' },
		series: [{ name: 'Articles', data: @json($counts) }],
		xaxis: { categories: @json($labels) },
		colors: ['#0d6efd']
	};
	var chart = new ApexCharts(document.querySelector('#articlesChart'), options);
	chart.render();

	// Status donut
	var donutOptions = {
		chart: { type: 'donut', height: 240 },
		series: [{{ $publishedCount }}, {{ $draftCount }}],
		labels: ['Published','Draft'],
		colors: ['#198754','#6c757d']
	};
	var donut = new ApexCharts(document.querySelector('#statusDonut'), donutOptions);
	donut.render();
});
</script>
@endpush

@endsection
