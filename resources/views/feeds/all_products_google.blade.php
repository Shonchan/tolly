<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
    <channel>
        <title>TOLLY.RU</title>
        <link>https://tolly.ru/</link>
        <description>TOLLY.RU</description>
         @foreach($variants as $variant)
        <item>
            <g:id>{{$variant->id}}</g:id>
            <g:condition>new</g:condition>
            <g:title>{{$variant->product->name}} {{$variant->name}}</g:title>
            <g:description>{{$variant->description}}</g:description>
            <g:link>https://tolly.ru/product/{{$variant->id}}</g:link>
            <g:image_link>{{$variant->product->image}}</g:image_link>
            <g:availability>@if($variant->stock > 1){{'in stock'}}@else{{'out of stock'}}@endif</g:availability>
            <g:price>{{$variant->price}} RUB</g:price>
            <g:brand>{{$variant->product->brand->name}}</g:brand>
            <g:mpn>{{$variant->external_id}}</g:mpn>
            <g:google_product_category>{{$variant->product->categories[0]->name}}</g:google_product_category>
        </item>
         @endforeach
    </channel>
</rss>
