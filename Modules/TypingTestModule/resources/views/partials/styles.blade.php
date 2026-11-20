@once
    @push('styles')
        <style>
            .typing-source {
                direction: rtl;
                text-align: justify;
                font-size: 1.35rem;
                line-height: 2.4rem;
                background: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                padding: 1rem 1.25rem;
                user-select: none;
                -webkit-user-select: none;
            }
            .typing-source .tw { padding: 0 2px; border-radius: 3px; }
            .typing-source .tw-correct { color: #155724; background: #d4edda; }
            .typing-source .tw-wrong {
                color: #721c24;
                background: #f8d7da;
                text-decoration: line-through;
                text-decoration-color: #dc3545;
                text-decoration-thickness: 2px;
            }
            .typing-source .tw-current { background: #fff3cd; border-bottom: 2px solid #ffc107; }
            .typing-source .tw-extra { color: #721c24; background: #f8d7da; text-decoration: line-through; }
            .typing-legend > span { display: inline-block; margin-left: 1rem; }
            .typing-legend .tt-swatch { display: inline-block; width: 14px; height: 14px; border: 1px solid #ccc; border-radius: 3px; vertical-align: middle; }
        </style>
    @endpush
@endonce
