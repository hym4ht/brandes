@props(['number', 'title', 'text', 'position' => 'left', 'target' => '', 'targetGroup' => ''])

<div class="custom-tooltip-info pos-{{ $position }}" data-step="{{ $number }}" data-target="{{ $target }}" data-target-group="{{ $targetGroup }}">
    @if($position === 'left')
        <div class="tooltip-box">
            <strong>{{ $title }}</strong>
            <p>{!! $text !!}</p>
        </div>
        <div class="tooltip-node">
            <span class="tooltip-number">{{ $number }}</span>
            <div class="tooltip-line"></div>
        </div>
    @elseif($position === 'right')
        <div class="tooltip-node">
            <div class="tooltip-line"></div>
            <span class="tooltip-number">{{ $number }}</span>
        </div>
        <div class="tooltip-box">
            <strong>{{ $title }}</strong>
            <p>{!! $text !!}</p>
        </div>
    @endif
</div>
