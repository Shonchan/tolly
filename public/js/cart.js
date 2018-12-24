// $(document).ready(function () {


    var opt = 2;

    var pvz = [
        {'name':'Академика Янгеля «Почтальон Сервис»','address':'Москва, ул. Россошанская, 3к1Ас2','phone':'8(495)668-07-33','time':'Пн-Сб: 10:00-21:00, Вс: 10:00-20:00','howGo':'"м. Улица Академика Янгеля. Последний вагон из центра, выход на ул. Россошанская, идти до дома 3к1Ас2, где расположено отделение «Сбербанк» и «Центр Торговли». Пункт выдачи расположен на цокольном этаже. Ориентир: вход рядом с ремонтом одежды."','coords':[55.595102, 37.607254]},
        {'name':'Багратионовская «Почтальон Сервис»','address':'Москва, ул. Барклая, 8, пав. 415','phone':'8(905)599-97-39','time':'Пн-Сб: 10:00-20:00','howGo':'Ст. метро Багратионовская. Последний вагон из центра; Из стеклянных дверей налево; Идете прямо в сторону ТЦ «Горбушка». Вам нужно зайти внутрь; дойти до лестницы; Подняться на 4 этаж, павильон 425.','coords':[55.741175, 37.502678]},
        {'name':'Белорусская «Почтальон Сервис»','address':'Москва, пл. Тверская Застава, 3','phone':'8(903)535-03-45','time':'Пн-Сб: 10:00-20:00','howGo':'Метро Белорусская (кольцевая) выход к Белорусскому вокзалу. Из метро налево, через 10 метров деревянная дверь, над ней вывеска с адресом, пройти левее от лестницы, выйти во двор. Напротив выхода дверь. Пункт Выдачи Заказов «Почтальон Сервис».','coords':[55.775404, 37.581710]},
        {'name':'Беляево «Почтальон Сервис»','address':'Москва, ул. Профсоюзная, 96','phone':'8(495)668-07-33','time':'Ежедневно: 10:00-21:00','howGo':'Последний вагон из центра, из стеклянных дверей направо,по переходу первый поворот налево. Двигаться по ул.Профсоюзной в направлении центра до дома 96, вход в конце дома. Белые стеклянные двери. Вывеска со стороны Профсоюзной ул.96 (дублёр ) ozon, ателье, турагенство. Офис № 4.','coords':[55.647894, 37.528428]},
        {'name':'Братиславская «Почтальон Сервис»','address':' Москва, ул. Братиславская, 14','phone':'8(903)112-71-20','time':'Ежедневно: 10:00-20:00','howGo':'Метро Братиславская последний вагон из центра, из метро налево, пройти вдоль дома номер 14, повернуть направо. Между подъездами 9 и 10 спуск в подвал.','coords':[55.658841, 37.756581]},
        {'name':'Бульвар Адмирала Ушакова «Почтальон Сервис»','address':'Москва, Чечёрский проезд, 8','phone':'8(495)668-07-33','time':'Ежедневно: 10:00-21:00','howGo':'Из метро налево мимо церкви до светофора. Переходите дорогу, справа огибаете жилой дом и идёте прямо вдоль школы. В конце школы налево, по правую руку 2-х этажный ТЦ серого цвета. Вывески Магнит, Шатура, мебель. Вход на 2 этаж, далее по указателям.','coords':[55.542024, 37.544634]},
        {'name':'Бунинская аллея «Почтальон Сервис»','address':'Москва, пос. Сосенское, ул. Александры Монаховой, 97','phone':'8(499)390-69-34','time':'Пн-Пт: 10:00-20:00, Сб-Вс: 10:00-18:00','howGo':'От метро Буннинская аллея, 7 остановок на маршрутке № 967 до остановки ЖК «Бунинский», первый подъезд Пункт Выдачи Заказов «Почтальон Сервис».','coords':[55.541550, 37.490056]},
        {'name':'ВДНХ «Почтальон Сервис»','address':'Москва, Звездный бульвар, 10c1','phone':'8(903)222-64-51','time':'Ежедневно: 10:00-21:00','howGo':'Последний вагон из центра, выход к Звездному бульвару. Идти прямо по Звездному бульвару до дома номер 10.','coords':[55.817069, 37.633300]},
        {'name':'Водный стадион «Почтальон Сервис»','address':'Москва, Кронштадтский бульвар, 7, пав. 222','phone':'8(925)090-40-10','time':'Ежедневно: 10:00-20:00','howGo':'Метро Водный Стадион, первый Вагон из центра. Из стеклянных дверей налево, пройти 50 метров до входа в ТЦ Крона. Подняться по эскалатору на второй этаж, повернуть направо. Следовать по коридору, никуда не сворачивая, до павильона № 222 с вывеской «Чердачный Арт» и «Пункт выдачи заказов «Почтальон Сервис». Для удобства поиска, при входе расположена карта павильонов ТЦ, и сотрудники охраны, которые смогут сориентировать получателей.','coords':[55.840932, 37.487548]},
        {'name':'Войковская «Почтальон Сервис»','address':'Москва, улица Зои и Александра Космодемьянских, 4к1','phone':'8(499)653-98-20','time':'Пн-Сб: 10:00-20:00','howGo':'Последний вагон из центра, выход к улице Зои и Александра Космодемьянских. Выйдя на улицу, повернуть налево, двигаться вдоль дома Ленинградское шоссе, 8/2с1Б, после дома повернуть направо. Перейти через трамвайные пути. За трамвайной остановкой белая, пластиковая входная группа с вывеской "СМЕШНЫЕ ЦЕНЫ" и пункт выдачи заказов. Зайти внутрь, спуститься по правой лестнице.','coords':[55.818073, 37.502165]},
        {'name':'Дубровка «Почтальон Сервис»','address':'Москва, ул. Шарикоподшипниковская, 13с2','phone':'8(495)668-07-33','time':'Пн-Пт: 11:00-20:00','howGo':'1 вагон из центра,ТК « Дубровка», 2 этаж, павильон № Л74. Вывеска «Пункт выдачи заказов».','coords':[55.718035, 37.677690]},
        {'name':'Каширская «Почтальон Сервис»','address':'Москва, Каширское шоссе, 24с7','phone':'8(903)112-51-42','time':'Пн-Сб: 10:00-20:00','howGo':'Первый вагон из центра Метро Каширская из метро направо, перейти через дорогу. Повернуть налево, идти вдоль дороги до высокого здания. Перед ним повернуть направо. Пройти через шлагбаум, повернуть направо. Центральный вход, повернуть направо, Комната № 2. Пункт Выдачи Заказов «Почтальон Сервис».','coords':[55.654071, 37.645278]},
        {'name':'Киевская «Почтальон Сервис»','address':'Москва, ул. Брянская, 2','phone':'8(495)668-07-33','time':'Пн-Вс: 10:00-21:00','howGo':'Выход из метро Киевская в сторону ж/д касс. Переходите дорогу до ТЦ Европейский, идете до перекрестка 2-й брянский пер. и переходите дорогу в сторону цветочных салонов. Второй подъезд от угла, надпись над входом "Центр красоты Стар Бьюти" из дверей лестница налево, после до конца по коридору, кабинет 208.','coords':[55.744417, 37.563499]},
        {'name':'Кунцево «Почтальон Сервис»','address':'Москва, ул. Толбухина, 13к2','phone':'8(495)668-07-33','time':'Пн-Пт: 11:00-20:00','howGo':'ЖД станция "Сетунь", перейти дорогу.4-х этажное здание красного цвета. Обходите его, справа за зданием увидите белую дверь, входите внутрь. 2 этаж кабинет №9','coords':[55.723412, 37.398484]},
        {'name':'Мичуринский проспект «Почтальон Сервис»','address':'Москва, улица Мичуринский Проспект, Олимпийская Деревня, 4к2','phone':'8(965)200-23-60','time':'Пн-Пт: 11:00-20:00, Сб: 11.00-18.00','howGo':'Метро Юго-Западная Последний вагон из центра. Из стеклянных дверей направо автобусы №227 №667 следуем до остановки "Олимпийская деревня - Музей обороны Москвы" далее от остановки направо, Пройдя 40 метров, держитесь левой стороны первое серое здание - "Дом Быта". Держитесь левой стороны будет 2 двери, входите в "Дом быта", поднимитесь по лестнице и входите в дверь где весит вывеска "Дом быта , далее по коридору до конца поворачиваете налево Офис № 21.','coords':[55.675381, 37.469112]},
        {'name':'Нагатинская «Почтальон Сервис»','address':'Москва, Варшавское ш., 39','phone':'8(499)394-51-79','time':'Пн-Пт: 10:00-20:00, Сб-Вс: 10:00-18:00','howGo':'Метро Нагатинская, последний вагон из центра, из стеклянных дверей направо, пройти до конца, повернуть налево, выйдя на улицу, перейти через трамвайные пути, вход в БЦ Нагатинский. После охраны направо, в лифт. 4-ый этаж, из лифта налево, офис 444. Пункт Выдачи Заказов «Почтальон Сервис».','coords':[55.684551, 37.624057]},
        {'name':'Новогиреево «Почтальон Сервис»','address':'Москва, Зеленый проспект, 62А','phone':'8(909)922-27-89','time':'Ежедневно: 10:00-20:00','howGo':'Первый вагон из цента, выход к Зеленому проспекту, ТРЦ Шангал. Далее в центральный вход Торгового центра и направо, на лифте «-2» этаж, из лифта повернуть налево, увидите табличку Пункт Выдачи заказов «Почтальон Сервис».','coords':[55.750908, 37.820440]},
        {'name':'Орехово «Почтальон Сервис»','address':'Москва, Шипиловский проезд, 43к2','phone':'8(495)668-07-33','time':'Пн-Пт: 10:00-20:30, Сб-Вс: 10:00-20:00','howGo':'М. Орехово, 1й вагон из центра, из стеклянных дверей налево и пройти до ТД «Белочка». От него поворачиваете направо и идете вдоль дома. Доходите до конца дома по парковке и поворачиваете налево за угол ,впереди увидете Зообункер, ТК «Лабиринт». Вход с левой стороны на цоколь, при спуске табличка «Пункт выдачи заказов», далее по указателям до каб. №1.','coords':[55.611669, 37.698994]},
        {'name':'Павелецкая «Почтальон Сервис»','address':'Москва, Большая Пионерская, 4, оф. 1-6','phone':'8(925)934-28-05','time':'Пн-Пт: 10:00-20:00, Сб-Вс: 10:00-18:00','howGo':'Метро Павелецкая (кольцевая), при выходе из метро налево, спуститься в пешеходный переход, по переходу первый поворот направо, выход на улицу, левее проход между двумя зданиями. пройти прямо до Большой пионерской. На Большой Пионерской, справа 2-х этажное здание. Войдя, пройдите по коридору до офиса 1-6, Пункт выдачи заказов.','coords':[55.729539, 37.634094]},
        {'name':'Парк Культуры «Почтальон Сервис»','address':'Москва, Зубовскиий бульвар, 16-20','phone':'8(495)668-07-33','time':'Пн-Пт: 10:00-20:00','howGo':'Выход на Зубовский бульвар, идти по четной стороне до дома 16-20, после здания РИА Новости следующий дом, вход с торца здания,первая дверь, написано 2 подъезд. Домофон Пункт Выдачи, Горячие туры.','coords':[55.738445, 37.588158]},
        {'name':'Парк Победы «Почтальон Сервис»','address':'Москва, ул. Поклонная, 11с1','phone':'8(967)079-37-02','time':'Пн-Пт: 11:00-20:00','howGo':'Выход к Поклонной горе, затем пройти до начала улицы Генерала Ермолова и повернуть налево на ул. Поклонная. Пройти мимо химчистки Диана, пройти следующего д. 13, до зелёных гаражей с вывеской "Московский городской Стрелковый клуб"стрелкой, указывающий вниз. Спуститься вниз до конца, повернуть налево и пройти через металлическую калитку прямо. Зайти в подъезд с вывеской "Московский городской Стрелковый клуб", пройти мимо охранника метров пять и повернуть направо, дойдя до Учебной части повернуть налево, пройти вперёд. Подняться на лестнице на второй этаж - офис 205.','coords':[55.733636, 37.523505]},
        {'name':'Пражская «Почтальон Сервис»','address':'Москва, ул. Кировоградская, 22Г','phone':'8(495)668-07-33','time':'Ежедневно: 10:00-21:00','howGo':'Метро Пражская. Последний вагон из центра, вперёд по Кировоградской улице до переулка, поворот налево, справа магазин Продукты, вход через магазин , по лестнице справа на 2 этаж и сразу налево. Таблички присутствуют.','coords':[55.614750, 37.604465]},
        {'name':'Пушкинская «Почтальон Сервис»','address':'Москва, Настасьинский переулок, 8с2','phone':'8(905)599-91-02','time':'Ежедневно: 10:00-20:00','howGo':'Первый вагон из центра. Выход в город к издательству «Известия». на Настасьинском переулке пройти между домами 8стр1 и 6стр5.','coords':[55.767260, 37.605408]},
        {'name':'Рязанский проспект «Почтальон Сервис»','address':'Москва, ул. 1-я Новокузьминская, 25','phone':'8(967)082-83-45','time':'Ежедневно: 10:00-21:00','howGo':'Метро Рязанский прспект. Последний вагон из центра, налево и ещё раз налево (спиной к Рязанскому проспекту), Далее вдоль торговых рядов до улицы 1-я Новокузьминская и налево, пройти школу. Вход с торца здания (слева). Напротив гостиница «Бригантина». Спускаетесь по лестнице, справа 2-я дверь "химчистка", за химчисткой железная дверь слева.','coords':[55.713813, 37.793918]},
        {'name':'Сокольники «Почтальон Сервис»','address':'Москва, Сокольническая пл., 4А','phone':'8(495)668-07-33','time':'Ежедневно: 10:00-20:00','howGo':'Метро Сокольники. Выходите из метро на Сокольническую площадь, идете против движения 80 метров до ТЦ "Русское раздолье". Пункт выдачи находится на третьем этаже, в офисе 306','coords':[55.789970, 37.678605]},
        {'name':'Солцево «Почтальон Сервис»','address':'Москва, Солнцевский проспект, 11','phone':'8(495)532-97-25','time':'Пн-Пт: 10:00-20:00, Сб-Вс: 10:00-18:00','howGo':'Метро «Солнцево». До Солнцевского проспекта д. 11. 4-й подъезд, домофон «102». Пункт Выдачи Заказов «Почтальон Сервис».','coords':[55.649621, 37.404255]},
        {'name':'Текстильщики «Почтальон Сервис»','address':'Москва, Люблинская, 27/2','phone':'8(499)179-02-45','time':'Пн-Пт: 12:00-19:00, Сб: 12:00-18:00','howGo':'Метро Текстильщики, последний вагон из центра. Двигаться по Люблинской улице, по стороне пруда «Садки». Следовать до пересечения с улицей 1-ая Текстильщиков. Двигаться по правой стороне, до Вывески «Дом Торговли и Услуг» и «Пункт Выдачи заказов».','coords':[55.700852, 37.733405]},
        {'name':'Теплый Стан «Почтальон Сервис»','address':'Москва, ул. Профсоюзная, д. 146, корп. 1','phone':'8(903)005-07-75','time':'Ежедневно: 10:00-20:00','howGo':'Последний вагон из центра, выход к кинотеатру «Аврора», ориентир магазин «Пятерочка». Вход через медицинский центр CMD.','coords':[55.623589, 37.506951]},
        {'name':'Юго-Западная «Почтальон Сервис»','address':'Москва, ул. 26-ти Бакинских Комиссаров, 14','phone':'8(963)929-24-75','time':'Пн-Пт: 11:00-20:00','howGo':'Последний вагон из центра. Из стеклянных дверей направо и направо по ступеням из перехода. После выхода из метро идти прямо по проспекту Вернадского в сторону ул. 26 Бакинских комиссаров: мимо д. 105 к. 3 («MENZA кафе», «Чайхона №1»), далее мимо д. 105 («Л’Этуаль», «Снежная Королева») и далее мимо д. 105 к. 1 («ВьетКафе») дойти до лестницы. Расстояние от метро до лестницы - 350 м. Подняться по лестнице. Сразу после подъема бело-салатовый 17 этажный жилой дом. Повернуть налево. Подъезд с внешней стороны в центре дома, правый вход. По коридору до конца левая дверь.','coords':[55.661610, 37.487163]}
    ];

// ---------- Yandex map ------------------ //

        ymaps.ready(init);
        function init() {
            // Создание карты.
            var mapw = $('div.tabs').width() - 30;

            $('#map').css('width', mapw+"px");

            var myMap = new ymaps.Map("map", {

                center: [55.76, 37.64],
                controls: ['geolocationControl','fullscreenControl','zoomControl'],
                zoom: 9
            });

            var ico = {

                iconLayout: 'default#image',
                iconImageHref: '../img/marker.svg',

                iconImageSize: [30, 42], // size of the icon

                // iconAnchor:   [15, 42], // point of the icon which will correspond to marker's location

                iconImageOffset: [-15, -42], // point from which the popup should open relative to the iconAnchor
                iconContentOffset: [0, -42]
            };

            var clusterer = new ymaps.Clusterer({
                gridSize: 80
            });

            var markers = [];


            for (var i = 0; i < pvz.length; i++) {
                // console.log(pvz[i]);
                var popup = "<div class=\"overlaymap\">" +
                    "<div class=\"overlaymap-name\"><img src=\"https://pp.userapi.com/c629221/v629221450/1dd46/l8r-1LDFCU8.jpg?ava=1\" alt=\"\"> " + pvz[i].name + "</div>" +
                    "<div class=\"overlaymap-text\">" + pvz[i].address + "<br>" +
                    pvz[i].time + "<br>" +
                    pvz[i].phone +
                    "</div>" +
                    "<div class=\"overlaymap-date\">" + $('#dateDelivery').val() + "</div>" +
                    "<div class=\"overlaymap-row\">" +
                    "<div class=\"overlaymap-price\">" +
                    "<span>" + $('input[name="delivery_price"]').val() + " руб.</span>" +
                    "<ul>" +
                    // "<li><span class=\"visa\"></span></li>" +
                    "<li><span class=\"cash\"></span></li>" +
                    "</ul>" +
                    "</div>" +
                    "<div class=\"overlaymap-primary\">" +
                    "<button type='button'  onclick=\"selectPoint(" + i + ", 2)\" class=\"btn1\">Выбрать</button>" +
                    "</div>" +
                    "</div>" +
                    "</div>";

                var mp = new ymaps.Placemark(pvz[i].coords, {
                    hintContent: pvz[i].name,
                    balloonContent: popup
                }, ico);

                markers[i] = mp;

                var point = '<div class="point" data-address="' + pvz[i].address.toLowerCase() + '"><div class="point-line"><b>' + pvz[i].name + ' ' + pvz[i].phone + '</b><span>' + pvz[i].address + '</span></div><div class="point-time">' + pvz[i].time + '</div><div class="point-cash">'+getDelPrice()+'</div><div class="point-btn"><button type="button" onclick="selectPoint(' + i + ', 1)" class="btn1">Выбрать</button></div></div>';
                $('#option1 .form-content').append(point);
            }

            clusterer.add(markers);
            myMap.geoObjects.add(clusterer);

            // myMap.setBounds(clusterer.getBounds(), {
            //     checkZoomRange: true
            // });
        }





        // var map = L.map('map').setView(L.latLng([55, 49]), 5);
        // ----------------  LEAFLET ----------------///////////////
/*        var map = L.map('map').setView(L.latLng([55.729539, 37.634094]), 9);
        L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '@ <a target="_blank" href="http://www.openstreetmap.org">OpenStreetMap.org</a> contributors',
            maxZoom: 18

        }).addTo(map);


        var ico = L.icon({
            iconUrl: '../img/marker.svg',
            // shadowUrl: 'leaf-shadow.png',

            iconSize:     [30, 42], // size of the icon
            // shadowSize:   [50, 64], // size of the shadow
            iconAnchor:   [15, 42], // point of the icon which will correspond to marker's location
            // shadowAnchor: [4, 62],  // the same for the shadow
            popupAnchor:  [0, -40] // point from which the popup should open relative to the iconAnchor
        });

        var markers = L.markerClusterGroup();

        $('#option1 .form-content').empty();
        for(var i=0; i<pvz.length; i++){
            // console.log(pvz[i]);
            var  popup = "<div class=\"overlaymap\">" +
                "<div class=\"overlaymap-name\"><img src=\"https://pp.userapi.com/c629221/v629221450/1dd46/l8r-1LDFCU8.jpg?ava=1\" alt=\"\"> "+pvz[i].name+"</div>" +
                "<div class=\"overlaymap-text\">" +    pvz[i].address +"<br>" +
                pvz[i].time+"<br>" +
                pvz[i].phone +
                "</div>" +
                "<div class=\"overlaymap-date\">"+$('#dateDelivery').val()+"</div>" +
                "<div class=\"overlaymap-row\">" +
                "<div class=\"overlaymap-price\">" +
                "<span>"+ $('input[name="delivery_price"]').val() +" руб.</span>" +
                "<ul>" +
                "<li><span class=\"visa\"></span></li>" +
                "<li><span class=\"cash\"></span></li>" +
                "</ul>" +
                "</div>" +
                "<div class=\"overlaymap-primary\">" +
                "<button type='button'  onclick=\"selectPoint("+i+")\" class=\"btn1\">Выбрать</button>" +
                "</div>" +
                "</div>" +
                "</div>";

            markers.addLayer(L.marker(new L.LatLng(pvz[i].coords[0], pvz[i].coords[1]), {icon:ico}).bindPopup(popup));

            var point = '<div class="point" data-address="'+pvz[i].address.toLowerCase()+'"><div class="point-line"><b>'+pvz[i].name+' '+pvz[i].phone+'</b><span>'+pvz[i].address+'</span></div><div class="point-time">'+pvz[i].time+'</div><div class="point-cash"><span class="text-green">Бесплатно</span></div><div class="point-btn"><button type="button" onclick="selectPoint('+i+')" class="btn1">Выбрать</button></div></div>';
            $('#option1 .form-content').append(point);
        }

        map.addLayer(markers);



    // $.ajax({
    //     type:"GET",
    //     url:"https://integration.cdek.ru/pvzlist/v1/json?countryid=1",
    //     dataType:"json",
    //     //beforeSend:function(){ $('#ul_pro').html('<div class="load"></div>'); },
    //     success:function(data){
    //         // console.log(data.pvz.length);
    //         pvz = data.pvz;
    //
    //         var map = L.map('map').setView(L.latLng([55, 49]), 5);
    //         L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    //             attribution: '@ <a target="_blank" href="http://www.openstreetmap.org">OpenStreetMap.org</a> contributors',
    //             maxZoom: 18
    //
    //         }).addTo(map);
    //
    //
    //
    //         var markers = L.markerClusterGroup();
    //
    //         for(var i=0; i<pvz.length; i++){
    //             // console.log(pvz[i]);
    //             markers.addLayer(L.marker(new L.LatLng(pvz[i].coordY, pvz[i].coordX)).bindPopup("<b>"+pvz[i].name+"</b><br>"+pvz[i].fullAddress));
    //         }
    //
    //         map.addLayer(markers);
    //     }
    // });



    function getRandomLatLng(m) {
        var bounds = m.getBounds(),
            southWest = bounds.getSouthWest(),
            northEast = bounds.getNorthEast(),
            lngSpan = northEast.lng - southWest.lng,
            latSpan = northEast.lat - southWest.lat;

        return new L.LatLng(
            southWest.lat + latSpan * Math.random(),
            southWest.lng + lngSpan * Math.random()
        );
    }*/

//    ---------------------- END LEAFLET ---------------------- //////

// });
function getDelPrice(){
    var delPrice = "<span class='text-green'>бесплатно</span>";
    if($('input[name="delivery_price"]').val()>0)
    {
        delPrice = "<span class='text-bold'>"+$('input[name="delivery_price"]').val() + " руб.</span><div><span class='cash'></span></div>";
    }
    return delPrice;
}

function selectPoint(ind, option) {



        var pv = pvz[ind];
        var comp = $("#field2 .complete");
        comp.find('.point-line b').html(pv.name + " " + pv.phone);
        comp.find('.point-line span').html(pv.address);
        comp.find('.point-time span').html($('input#dateDelivery').val());
        comp.find('.point-cash span').html(getDelPrice());
        comp.find('p.how-to-go').html(pv.howGo);
        comp.find('p.time').html(pv.time);
        comp.show();


    $('div#option1').hide();
    $('div#option2').hide();
    $('.field2h').hide();
    // $('div#field2').hide();
    // opt = option;
    // console.log('#option'+ opt);

    $('html, body').animate({
        scrollTop: comp.offset().top - 50
    }, 200);

    $('textarea[name="comment"]').val("Точка самовывоза - "+pv.name+"\n"+pv.phone+"\n"+pv.address);



}


function setDeliveryPrice() {
    if( $('input[name="delivery_price"]').val() == 0) {
        $('#delivery-price span').html("бесплатно");
        $('#delivery-price span').addClass('text-green');
    } else {
        $('#delivery-price span').html($('input[name="delivery_price"]').val()+" руб.");
        $('#delivery-price span').removeClass('text-green');
    }
    var total = parseInt($('input[name="total_sum"]').val()) - parseInt($('input[name="discount_value"]').val()) + parseInt($('input[name="delivery_price"]').val());
    $('.order-sidebar-complete li.text-bold span').html(total+" руб.");
}
$(document).ready(function () {

    $(window).resize(function () {
        var mapw = $('div.tabs').width() - 30;

        $('#map').css('width', mapw+"px");
    });

    $('#option1 input').keyup(function () {

        var query = $(this).val().toLowerCase();
        // alert(query);
        if(query.length > 0) {
            $('#option1 > .form-content > .point').hide();
            $('#option1 > .form-content > .point[data-address *= "' + query + '"]').show();
        } else {
            $('#option1 > .form-content > .point').show();
        }
    });

    $('div.remove button').click(function (e) {
        e.preventDefault();
        $("#field2 .complete").hide()
        // console.log('#option'+ opt);
        $('div#option1').removeAttr("style");
        $('div#option2').removeAttr("style");
        $('.field2h').removeAttr("style");
        // $('div#field2').removeAttr("style");
        $('html, body').animate({
            scrollTop: $('#field2').offset().top - 50
        }, 200);

        $('textarea[name="comment"]').val('');
    });

    $('input[name="delivery"]').change(function () {

       if($(this).val()==3) {
           $('input[name="delivery_price"]').val($(this).data('price'));
           $('div#field2').removeAttr("style");
       } else {
           $('input[name="delivery_price"]').val($('input[name="area"]:checked').val());
       }

       setDeliveryPrice();
    });

    $('input[name="payment"]').change(function () {

        if($(this).val()==3) {

            $('.order-sidebar-button button').text("ПЕРЕЙТИ К ОПЛАТЕ");
        } else {
           $('.order-sidebar-button button').text("ПОДТВЕРДИТЬ ЗАКАЗ");
        }


    });

    $('input[name="area"]').change(function () {

        $('input[name="delivery_price"]').val($(this).val());
        setDeliveryPrice();

    });



    $('#store-form').change(function () {
        var data = $(this).serializeArray();
        var fields = 0;
        for(var i = 0; i<data.length; i++) {
            fields += validateField(data[i].name, data[i].value)
        }
        if(fields == 5) {
            // console.log($('#store-form .order-sidebar-button button'));
            $('#store-form .order-sidebar-button button').attr('disabled', false);
        } else {
            $('#store-form .order-sidebar-button button').attr('disabled', true);
        }
        // console.log(fields);
    });



});

function validateField(name, value) {
    if(name == 'name') {
        if (value.length >= 2) {
            $('#store-form input[name="name"]').closest('.form-group').removeClass('has-error');
            return 1;
        } else {
            $('#store-form input[name="name"]').closest('.form-group').addClass('has-error');
            return 0;
        }
    }
    if(name == 'lastname') {
        if (value.length >= 2) {
            $('#store-form input[name="'+name+'"]').closest('.form-group').removeClass('has-error');
            return 1;
        } else {
            $('#store-form input[name="'+name+'"]').closest('.form-group').addClass('has-error');
            return 0;
        }
    }

    if(name == 'phone') {
        if (value.search(/^\+7\s\(\d{3}\)\s\d{3}-\d{2}-\d{2}$/) > -1) {
            $('#store-form input[name="'+name+'"]').closest('.form-group').removeClass('has-error');
            return 1;
        } else {
            $('#store-form input[name="'+name+'"]').closest('.form-group').addClass('has-error');
            return 0;
        }
    }

    if(name == 'email') {
        if (value.length > 0) {

            if (value.search(/^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9_\-\.]+\.[a-zA-Z]{2,5}$/) > -1) {
                $('#store-form input[name="' + name + '"]').closest('.form-group').removeClass('has-error');
                return 0;
            } else {
                $('#store-form input[name="' + name + '"]').closest('.form-group').addClass('has-error');
                return -1;
            }

        }
        return 0;
    }

    if(name == 'delivery') {
        if(value.length > 0) {
          return 1;
        }
        return 0;
    }

    if(name == 'payment') {
        if(value.length > 0) {
            return 1;
        }
        return 0;
    }


    return 0;
}