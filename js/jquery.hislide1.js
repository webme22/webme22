(function($) {

    var slide = function(ele,options) {
        var $ele = $(ele);

        var setting = {

            speed: 350,

            interval: 2000,
            
        };

        $.extend(true, setting, options);

        var states = [
            { $zIndex: 1, width: 150, height: 100, top: 69, left: 200, $opacity: 0.2 },
            { $zIndex: 2, width: 200, height: 170, top: 59, left: 140, $opacity: 0.4 },
            { $zIndex: 3, width: 300, height: 218, top: 35, left: 190, $opacity: 0.7 },
            { $zIndex: 4, width: 400, height: 288, top: 0,  left: 300, $opacity: 1 },
            { $zIndex: 3, width: 300, height: 218, top: 35, left: 500, $opacity: 0.7 },
            { $zIndex: 2, width: 200, height: 170, top: 59, left: 650, $opacity: 0.4 },
            { $zIndex: 1, width: 150, height: 100, top: 69, left: 500, $opacity: 0.2 }
        ];

        var $lis = $ele.find('li');
        var timer = null;


        $ele.find('.hi-next').on('click', function() {
            next();
        });
        $ele.find('.hi-prev').on('click', function() {
            states.push(states.shift());
            move();
        });
        $ele.on('mouseenter', function() {
            clearInterval(timer);
            timer = null;
        }).on('mouseleave', function() {
           
        });

        move();
        autoPlay();


        function move() {
            $lis.each(function(index, element) {
                var state = states[index];
                $(element).css('zIndex', state.$zIndex).finish().animate(state, setting.speed).find('img').css('opacity', state.$opacity);
            });
        }


        function next() {

            states.unshift(states.pop());
            move();
        }

        function autoPlay() {
            timer = setInterval(next, setting.interval);
        }
    }

    $.fn.hiSlide = function(options) {
        $(this).each(function(index, ele) {
            slide(ele,options);
        });

        return this;
    }
})(jQuery);
