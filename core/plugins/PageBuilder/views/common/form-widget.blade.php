<div class="form-widget-section common-form-widget-section form-style-{{$data['form_style']}}" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if(!empty($data['title']))
                    <h3 class="form-title text-center mb-3">{{$data['title']}}</h3>
                @endif
                
                @if(!empty($data['description']))
                    <p class="form-description text-center mb-4">{{$data['description']}}</p>
                @endif
                
                <div class="contact-form">
                    <form action="{{route('tenant.frontend.form.submit')}}" method="POST" class="common-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <input type="text" name="name" class="form-control" placeholder="{{__('Your Name')}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <input type="email" name="email" class="form-control" placeholder="{{__('Your Email')}}" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <input type="text" name="subject" class="form-control" placeholder="{{__('Subject')}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <textarea name="message" class="form-control" rows="5" placeholder="{{__('Your Message')}}" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    {{__('Send Message')}}
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <div class="success-message" style="display:none; margin-top: 20px; padding: 15px; background: #d4edda; color: #155724; border-radius: 5px;">
                        {{$data['success_message']}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-style-boxed .contact-form {
    padding: 40px;
    background: #f8f9fa;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.form-style-inline .row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.form-style-inline .col-md-6,
.form-style-inline .col-md-12 {
    flex: 1;
    min-width: 200px;
}
</style>

