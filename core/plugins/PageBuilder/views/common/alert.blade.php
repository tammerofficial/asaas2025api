<div class="alert-section common-alert-section alert-{{$data['style']}}" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="alert-container">
        <div class="alert alert-{{$data['type']}} {{$data['dismissible'] === 'yes' ? 'alert-dismissible' : ''}}" 
             role="alert">
            <div class="alert-content">
                @if(!empty($data['icon']))
                    <i class="alert-icon {{$data['icon']}}"></i>
                @endif
                <div class="alert-body">
                    @if(!empty($data['title']))
                        <h5 class="alert-title">{{$data['title']}}</h5>
                    @endif
                    <p class="alert-message">{{$data['message']}}</p>
                </div>
            </div>
            @if($data['dismissible'] === 'yes')
                <button type="button" class="btn-close" onclick="this.closest('.alert').remove()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            @endif
        </div>
    </div>
</div>

<style>
.alert-section {
    padding: 20px 0;
}

.alert-container {
    width: 100%;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    display: flex;
    align-items: flex-start;
    gap: 15px;
    position: relative;
}

.alert-content {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    flex: 1;
}

.alert-icon {
    font-size: 1.5rem;
    margin-top: 2px;
}

.alert-body {
    flex: 1;
}

.alert-title {
    margin: 0 0 5px 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.alert-message {
    margin: 0;
    line-height: 1.6;
}

/* Alert Types */
.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-success .alert-icon {
    color: #28a745;
}

.alert-error {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.alert-error .alert-icon {
    color: #dc3545;
}

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
}

.alert-warning .alert-icon {
    color: #ffc107;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.alert-info .alert-icon {
    color: #17a2b8;
}

/* Alert Styles */
.alert-default {
    border-left: 4px solid;
}

.alert-filled {
    border: none;
}

.alert-outlined {
    background-color: transparent;
    border: 2px solid;
}

.alert-outlined.alert-success {
    border-color: #28a745;
    color: #28a745;
}

.alert-outlined.alert-error {
    border-color: #dc3545;
    color: #dc3545;
}

.alert-outlined.alert-warning {
    border-color: #ffc107;
    color: #856404;
}

.alert-outlined.alert-info {
    border-color: #17a2b8;
    color: #17a2b8;
}

/* Dismissible */
.alert-dismissible {
    padding-right: 50px;
}

.btn-close {
    position: absolute;
    top: 10px;
    right: 15px;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: inherit;
    opacity: 0.5;
    transition: opacity 0.3s;
}

.btn-close:hover {
    opacity: 1;
}

@media (max-width: 768px) {
    .alert {
        padding: 12px 15px;
    }
    
    .alert-icon {
        font-size: 1.2rem;
    }
}
</style>

