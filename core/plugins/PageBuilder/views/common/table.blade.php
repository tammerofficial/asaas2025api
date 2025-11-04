<div class="table-section common-table-section table-responsive-{{$data['responsive']}}" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        @if(array_key_exists('repeater_cells_', $data['repeater_data'] ?? []))
            <div class="table-wrapper">
                <table class="data-table table-{{$data['border_style']}} {{$data['striped_rows'] === 'yes' ? 'table-striped' : ''}}">
                    @if($data['header_row'] === 'yes' && !empty($data['repeater_data']['repeater_cells_'][0] ?? []))
                        <thead>
                            <tr>
                                @foreach($data['repeater_data']['repeater_cells_'][0] ?? [] as $cell)
                                    <th>{{$cell['cell_content_'] ?? ''}}</th>
                                @endforeach
                            </tr>
                        </thead>
                    @endif
                    <tbody>
                        @php
                            $start_index = $data['header_row'] === 'yes' ? 1 : 0;
                        @endphp
                        @foreach(array_slice($data['repeater_data']['repeater_cells_'] ?? [], $start_index) as $row_index => $row)
                            <tr>
                                @foreach($row ?? [] as $cell)
                                    <td>{{$cell['cell_content_'] ?? ''}}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<style>
.table-section {
    padding: 20px 0;
}

.table-wrapper {
    overflow-x: auto;
    width: 100%;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
}

.data-table th,
.data-table td {
    padding: 12px 15px;
    text-align: left;
}

.data-table th {
    background-color: var(--main-color-one, #007bff);
    color: #fff;
    font-weight: 600;
}

.table-solid th,
.table-solid td {
    border: 1px solid #ddd;
}

.table-dashed th,
.table-dashed td {
    border: 1px dashed #ddd;
}

.table-none th,
.table-none td {
    border: none;
}

.table-striped tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

.data-table tbody tr:hover {
    background-color: #e9ecef;
}

/* Responsive - Scroll */
.table-responsive-scroll .table-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table-responsive-scroll .data-table {
    min-width: 600px;
}

/* Responsive - Stack */
@media (max-width: 768px) {
    .table-responsive-stack .data-table,
    .table-responsive-stack thead,
    .table-responsive-stack tbody,
    .table-responsive-stack th,
    .table-responsive-stack td,
    .table-responsive-stack tr {
        display: block;
    }
    
    .table-responsive-stack thead tr {
        position: absolute;
        top: -9999px;
        left: -9999px;
    }
    
    .table-responsive-stack tr {
        border: 1px solid #ccc;
        margin-bottom: 10px;
    }
    
    .table-responsive-stack td {
        border: none;
        border-bottom: 1px solid #eee;
        position: relative;
        padding-left: 50% !important;
    }
    
    .table-responsive-stack td:before {
        content: attr(data-label);
        position: absolute;
        left: 6px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
        font-weight: 600;
    }
}
</style>

