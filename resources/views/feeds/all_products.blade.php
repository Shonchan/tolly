<yml_catalog date="{{$date}}">
    <shop>
        <name>TOLLY</name>
        <company>TOLLY</company>
        <url>https://tolly.ru/</url>

        <currencies>
            <currency id="RUR" rate="1"/>
        </currencies>

        <categories>
            @foreach($categories as $category)
            <category id="{{$category->id}}"@if($category->parent_id > 0) parentId="{{$category->parent_id}}"@endif>{{$category->name}}</category>
            @endforeach
        </categories>

        <offers>
            @foreach($variants as $variant)
            <offer id="{{$variant->id}}" available="@if($variant->stock > 1){{'true'}}@else{{'false'}}@endif">
                <url>https://tolly.ru/product/{{$variant->id}}</url>
                <price>{{$variant->price}}</price>
                <currencyId>RUR</currencyId>
                <categoryId>{{$variant->product->categories[0]->id}}</categoryId>
                <picture>{{$variant->product->image}}</picture>
                <store>@if($variant->stock > 1){{'true'}}@else{{'false'}}@endif</store>
                <pickup>true</pickup>
                <delivery>true</delivery>
                <vendor>{{$variant->product->brand->name}}</vendor>
                <name>{{$variant->product->name}}@if($variant->product->seo) {{$variant->name}}, {{$variant->product->seo}}@else {{$variant->name}}@endif</name>
                <description>{{$variant->description}}</description>

                @foreach($variant->options as $option)
                <param name="{{$option['name']}}">{{$option['value']}}</param>
                @endforeach
            </offer>
            @endforeach
        </offers>
    </shop>
</yml_catalog>
