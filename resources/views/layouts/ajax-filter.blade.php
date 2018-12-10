

            @foreach ($features as $f)
                <div class="filter-collapse">
                    <div class="filter-collapse-name">{{ $f->name }}</div>
                    <div class="filter-collapse-hide">
                        <div class="nano">
                            <div class="nano-content">
                                <ul class="filter-menu">
                                    @foreach ($f->foptions()->groupBy('value')->get() as $o)
                                        <li class="filter-field"><div class="checkbox"><input type="checkbox" name="features[{{ $f->id }}][]" value="{{ $o->value }}"><span>{{ $o->value }}</span></div></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
