<div class="breadcrumb-section common-breadcrumb-section breadcrumb-{{$data['style']}}" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}"
     style="text-align: {{$data['alignment']}};">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb-list">
            @foreach($data['breadcrumbs'] as $index => $crumb)
                <li class="breadcrumb-item {{$crumb['active'] ? 'active' : ''}}">
                    @if($crumb['active'])
                        <span class="breadcrumb-text">{{$crumb['title']}}</span>
                    @else
                        <a href="{{$crumb['url']}}" class="breadcrumb-link">{{$crumb['title']}}</a>
                    @endif
                    @if(!$crumb['active'] && $index < count($data['breadcrumbs']) - 1)
                        <span class="breadcrumb-separator">{{$data['separator']}}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
</div>

<style>
.breadcrumb-section {
    padding: 15px 0;
}

.breadcrumb-list {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 8px;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.breadcrumb-link {
    color: var(--main-color-one, #007bff);
    text-decoration: none;
    transition: color 0.3s;
}

.breadcrumb-link:hover {
    color: var(--main-color-two, #0056b3);
    text-decoration: underline;
}

.breadcrumb-text {
    color: #666;
}

.breadcrumb-item.active .breadcrumb-text {
    color: #333;
    font-weight: 600;
}

.breadcrumb-separator {
    color: #999;
    margin: 0 4px;
}

/* Minimal Style */
.breadcrumb-minimal .breadcrumb-link {
    color: #666;
}

.breadcrumb-minimal .breadcrumb-separator {
    color: #ccc;
}

/* Arrows Style */
.breadcrumb-arrows .breadcrumb-separator {
    color: var(--main-color-one, #007bff);
    font-weight: 600;
}

@media (max-width: 768px) {
    .breadcrumb-list {
        font-size: 0.9rem;
    }
}
</style>

