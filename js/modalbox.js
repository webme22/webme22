/******************************************
 *
 *   Modalbox v1.1 - jQuery
 *
 ******************************************/
(function ($) {
    let d = 0;
    $.fn.myOwnDialog = function (options) {
        if (options === "open") {
            return this.each(function () {
                let modal_box = "rest";
                let modal_x = 0;
                let modal_y = 0;
                let modal_box_left = 0;
                let modal_box_top = 0;
                let modal_box_width = 0;
                let modal_box_height = 0;
                let modal_box_new_left = 0;
                let modal_box_new_top = 0;

                let modal_box_bottom_from_top = 0;
                let modal_box_bottom_dust = 0;

                let modal_box_right_from_left = 0;
                let modal_box_right_dust = 0;

                let modal_box_top_dust = 0;
                let modal_box_bottom_fixed = 0;

                let modal_box_left_dust = 0;
                let modal_box_right_fixed = 0;

                let dialog_id = $(this).attr("data-dialogId");
                let dialog_resize = $(this).attr("data-dialogResizable");
                let dialog_move = $(this).attr("data-dialogMovable");
                let dialog_auto_close = $(this).attr("data-dialogAutoClose");
                let dialog_touch_outside_close = $(this).attr("data-dialogTouchOutsideForClose");

                let $moDialog = $(this);
                $(".myOwnDialog_overlay" + dialog_id).css(
                    {
                        "display": "block"
                    });
                $(this).fadeIn();

                if (dialog_touch_outside_close === "true") {
                    $(document).on("mouseup",function (e) {
                        if (modal_box==="rest" && !$moDialog.is(e.target) && $moDialog.has(e.target).length === 0) {
                            $moDialog.fadeOut();
                            $(".myOwnDialog_overlay" + dialog_id).css(
                                {
                                    "display": "none"
                                });
                        }
                    });
                }

                $(this).find(".close").on("click", function () {
                    $moDialog.fadeOut();
                    $(".myOwnDialog_overlay" + dialog_id).css(
                        {
                            "display": "none"
                        });
                });
                if (dialog_auto_close !== "false") {
                    setTimeout(function () {
                        $moDialog.fadeOut();
                        $(".myOwnDialog_overlay" + dialog_id).css(
                            {
                                "display": "none"
                            });
                    }, dialog_auto_close);
                }

                if (dialog_move === "true") {
                    $(this).find(".titlebar_left").on("mousedown",function (e) {
                        if (modal_box === "rest") {
                            modal_x = e.pageX;
                            modal_y = e.pageY;
                            modal_box_left = $moDialog.position().left;
                            modal_box_top = $moDialog.position().top;
                            modal_box_width = $moDialog.width();
                            modal_box_height = $moDialog.height();
                            modal_box_new_left = e.pageX - modal_box_left;
                            modal_box_new_top = e.pageY - modal_box_top;
                        }
                        modal_box = "move";
                    });
                }
                if (dialog_resize === "true") {
                    $(this).find(".mdl_bottom").on("mousedown",function (e) {
                        if (modal_box === "rest") {
                            modal_x = e.pageX;
                            modal_y = e.pageY;
                            modal_box_left = $moDialog.position().left;
                            modal_box_top = $moDialog.position().top;
                            modal_box_width = $moDialog.width();
                            modal_box_height = $moDialog.height();
                            modal_box_new_left = e.pageX - modal_box_left;
                            modal_box_new_top = e.pageY - modal_box_top;

                            modal_box_bottom_from_top = modal_box_top + modal_box_height;
                            modal_box_bottom_dust = modal_box_bottom_from_top - modal_y;

                        }
                        modal_box = "bottom_resize";
                    });
                    $(this).find(".mdl_right").on("mousedown",function (e) {
                        if (modal_box === "rest") {
                            modal_x = e.pageX;
                            modal_y = e.pageY;
                            modal_box_left = $moDialog.position().left;
                            modal_box_top = $moDialog.position().top;
                            modal_box_width = $moDialog.width();
                            modal_box_height = $moDialog.height();
                            modal_box_new_left = e.pageX - modal_box_left;
                            modal_box_new_top = e.pageY - modal_box_top;

                            modal_box_right_from_left = modal_box_left + modal_box_width;
                            modal_box_right_dust = modal_box_right_from_left - modal_x;

                        }
                        modal_box = "right_resize";
                    });
                    $(this).find(".mdl_bottom_right").on("mousedown",function (e) {
                        if (modal_box === "rest") {
                            modal_x = e.pageX;
                            modal_y = e.pageY;
                            modal_box_left = $moDialog.position().left;
                            modal_box_top = $moDialog.position().top;
                            modal_box_width = $moDialog.width();
                            modal_box_height = $moDialog.height();
                            modal_box_new_left = e.pageX - modal_box_left;
                            modal_box_new_top = e.pageY - modal_box_top;

                            modal_box_bottom_from_top = modal_box_top + modal_box_height;
                            modal_box_bottom_dust = modal_box_bottom_from_top - modal_y;
                            modal_box_right_from_left = modal_box_left + modal_box_width;
                            modal_box_right_dust = modal_box_right_from_left - modal_x;

                        }
                        modal_box = "bottom_right_resize";
                    });
                    $(this).find(".mdl_top").on("mousedown",function (e) {
                        if (modal_box === "rest") {
                            modal_x = e.pageX;
                            modal_y = e.pageY;
                            modal_box_left = $moDialog.position().left;
                            modal_box_top = $moDialog.position().top;
                            modal_box_width = $moDialog.width();
                            modal_box_height = $moDialog.height();
                            modal_box_new_left = e.pageX - modal_box_left;
                            modal_box_new_top = e.pageY - modal_box_top;

                            modal_box_top_dust = modal_box_new_top;
                            modal_box_bottom_fixed = modal_box_top + modal_box_height;
                        }
                        modal_box = "top_resize";
                    });
                    $(this).find(".mdl_left").on("mousedown",function (e) {
                        if (modal_box === "rest") {
                            modal_x = e.pageX;
                            modal_y = e.pageY;
                            modal_box_left = $moDialog.position().left;
                            modal_box_top = $moDialog.position().top;
                            modal_box_width = $moDialog.width();
                            modal_box_height = $moDialog.height();
                            modal_box_new_left = e.pageX - modal_box_left;
                            modal_box_new_top = e.pageY - modal_box_top;

                            modal_box_left_dust = modal_box_new_left;
                            modal_box_right_fixed = modal_box_left + modal_box_width;
                        }
                        modal_box = "left_resize";
                    });
                    $(this).find(".mdl_top_left").on("mousedown",function (e) {
                        if (modal_box === "rest") {
                            modal_x = e.pageX;
                            modal_y = e.pageY;
                            modal_box_left = $moDialog.position().left;
                            modal_box_top = $moDialog.position().top;
                            modal_box_width = $moDialog.width();
                            modal_box_height = $moDialog.height();
                            modal_box_new_left = e.pageX - modal_box_left;
                            modal_box_new_top = e.pageY - modal_box_top;

                            modal_box_left_dust = modal_box_new_left;
                            modal_box_right_fixed = modal_box_left + modal_box_width;
                            modal_box_top_dust = modal_box_new_top;
                            modal_box_bottom_fixed = modal_box_top + modal_box_height;
                        }
                        modal_box = "top_left_resize";
                    });
                    $(this).find(".mdl_top_right").on("mousedown",function (e) {
                        if (modal_box === "rest") {
                            modal_x = e.pageX;
                            modal_y = e.pageY;
                            modal_box_left = $moDialog.position().left;
                            modal_box_top = $moDialog.position().top;
                            modal_box_width = $moDialog.width();
                            modal_box_height = $moDialog.height();
                            modal_box_new_left = e.pageX - modal_box_left;
                            modal_box_new_top = e.pageY - modal_box_top;

                            modal_box_right_from_left = modal_box_left + modal_box_width;
                            modal_box_right_dust = modal_box_right_from_left - modal_x;
                            modal_box_top_dust = modal_box_new_top;
                            modal_box_bottom_fixed = modal_box_top + modal_box_height;
                        }
                        modal_box = "top_right_resize";
                    });
                    $(this).find(".mdl_bottom_left").on("mousedown",function (e) {
                        if (modal_box === "rest") {
                            modal_x = e.pageX;
                            modal_y = e.pageY;
                            modal_box_left = $moDialog.position().left;
                            modal_box_top = $moDialog.position().top;
                            modal_box_width = $moDialog.width();
                            modal_box_height = $moDialog.height();
                            modal_box_new_left = e.pageX - modal_box_left;
                            modal_box_new_top = e.pageY - modal_box_top;

                            modal_box_bottom_from_top = modal_box_top + modal_box_height;
                            modal_box_bottom_dust = modal_box_bottom_from_top - modal_y;
                            modal_box_left_dust = modal_box_new_left;
                            modal_box_right_fixed = modal_box_left + modal_box_width;

                        }
                        modal_box = "bottom_left_resize";
                    });
                }

                $(this).on("mouseup",function () {
                    modal_box = "rest";
                    $(document).css(
                        {
                            "cursor": "default"
                        });
                });
                $(document).on("mouseup",function () {
                    modal_box = "rest";
                    $(document).css(
                        {
                            "cursor": "default"
                        });
                });
                $(document).on("mousemove",function (e) {
                    if (modal_box === "move") {
                        $moDialog.css(
                            {
                                "left": (e.pageX - modal_box_new_left) + "px",
                                "top": (e.pageY - modal_box_new_top) + "px"

                            });
                        $(document).css(
                            {
                                "cursor": "move"
                            });
                    } else if (modal_box === "bottom_resize") {
                        $moDialog.css(
                            {
                                "height": (e.pageY - modal_box_top + modal_box_bottom_dust) + "px"

                            });
                        $(document).css(
                            {
                                "cursor": "s-resize"
                            });
                    } else if (modal_box === "right_resize") {
                        $moDialog.css(
                            {
                                "width": (e.pageX - modal_box_left + modal_box_right_dust) + "px"
                            });
                        $(document).css(
                            {
                                "cursor": "e-resize"
                            });
                    } else if (modal_box === "bottom_right_resize") {
                        $moDialog.css(
                            {
                                "width": (e.pageX - modal_box_left + modal_box_right_dust) + "px",
                                "height": (e.pageY - modal_box_top + modal_box_bottom_dust) + "px"
                            });
                        $(document).css(
                            {
                                "cursor": "se-resize"
                            });
                    } else if (modal_box === "top_resize") {
                        $moDialog.css(
                            {
                                "top": (e.pageY - modal_box_top_dust) + "px",
                                "height": (modal_box_bottom_fixed - (e.pageY - modal_box_top_dust)) + "px"

                            });
                        $(document).css(
                            {
                                "cursor": "n-resize"
                            });
                    } else if (modal_box === "left_resize") {
                        $moDialog.css(
                            {
                                "left": (e.pageX - modal_box_left_dust) + "px",
                                "width": (modal_box_right_fixed - (e.pageX - modal_box_left_dust)) + "px"

                            });
                        $(document).css(
                            {
                                "cursor": "w-resize"
                            });
                    } else if (modal_box === "top_left_resize") {
                        $moDialog.css(
                            {
                                "left": (e.pageX - modal_box_left_dust) + "px",
                                "width": (modal_box_right_fixed - (e.pageX - modal_box_left_dust)) + "px",
                                "top": (e.pageY - modal_box_top_dust) + "px",
                                "height": (modal_box_bottom_fixed - (e.pageY - modal_box_top_dust)) + "px"

                            });
                        $(document).css(
                            {
                                "cursor": "nw-resize"
                            });
                    } else if (modal_box === "top_right_resize") {
                        $moDialog.css(
                            {
                                "width": (e.pageX - modal_box_left + modal_box_right_dust) + "px",
                                "top": (e.pageY - modal_box_top_dust) + "px",
                                "height": (modal_box_bottom_fixed - (e.pageY - modal_box_top_dust)) + "px"

                            });
                        $(document).css(
                            {
                                "cursor": "ne-resize"
                            });
                    } else if (modal_box === "bottom_left_resize") {
                        $moDialog.css(
                            {
                                "left": (e.pageX - modal_box_left_dust) + "px",
                                "width": (modal_box_right_fixed - (e.pageX - modal_box_left_dust)) + "px",
                                "height": (e.pageY - modal_box_top + modal_box_bottom_dust) + "px"

                            });
                        $(document).css(
                            {
                                "cursor": "sw-resize"
                            });
                    }
                });
            });
        } else {
            return this.each(function () {
                d += 1;
                let window_w = $(window).width();
                let window_h = $(window).height();
                let $moDialog = $(this);
                let settings = $.extend(
                    {
                        autoClose: false,
                        pos_x: "empty",
                        pos_y: "empty",
                        width: "300",
                        height: "200",
                        bg_color: 'white',
                        body_margin: "5",
                        body_overflow_x: "hidden",
                        body_overflow_y: "hidden",
                        movable: true,
                        resizable: true,
                        title: "  ",
                        touchOutsideForClose: false
                    }, options);
                if (settings.pos_x === "empty") {
                    settings.pos_x = (window_w - settings.width) / 2;
                }
                if (settings.pos_y === "empty") {
                    settings.pos_y = (window_h - settings.height) / 2;
                }
                let innerBody = $moDialog.html();
                $moDialog.html("<div class=\"mdl_box_body\">" + innerBody + "</div>");
                $moDialog.prepend("<div class=\"titlebar noselect\"><div class=\"titlebar_left\"><div class=\"titlebar_title\">" + settings.title + "</div></div><div class=\"titlebar_right\"><div class=\"close\">&times;</div></div><div class=\"box_clear\"></div></div>");

                $moDialog.append("<div class=\"mdl_top\"></div><div class=\"mdl_top_left\"></div><div class=\"mdl_left\"></div><div class=\"mdl_bottom_left\"></div><div class=\"mdl_bottom\"></div><div class=\"mdl_bottom_right\"></div><div class=\"mdl_right\"></div><div class=\"mdl_top_right\"></div>");
                $moDialog.attr("data-dialogId", d);
                $moDialog.attr("data-dialogResizable", settings.resizable);
                $moDialog.attr("data-dialogMovable", settings.movable);
                $moDialog.attr("data-dialogAutoClose", settings.autoClose);
                $moDialog.attr("data-dialogTouchOutsideForClose", settings.touchOutsideForClose);

                $(this).css(
                    {
                        "position": "fixed",
                        "z-index": "999",
                        "display": "none",
                        "width": settings.width + "px",
                        "height": settings.height + "px",
                        "left": settings.pos_x + "px",
                        "top": settings.pos_y + "px",
                        "background": settings.bg_color
                    });
                $(this).find(".mdl_box_body").css(
                    {
                        "margin": settings.body_margin + "px",
                        "overflow-x": settings.body_overflow_x,
                        "overflow-y": settings.body_overflow_y
                    });
                $(this).addClass("noselect");

                if (!$(".myOwnDialog_overlay").is()) {
                    $("body").prepend("<div class=\"myOwnDialog_overlay" + d + "\"></div>");
                }
                $(".myOwnDialog_overlay" + d).css(
                    {
                        "position": "fixed",
                        "background-color": "black",
                        "width": "100%",
                        "height": "100%",
                        "opacity": "0.5",
                        "left": "0",
                        "top": "0",
                        "right": "0",
                        "bottom": "0",
                        "display": "none"
                    });
            });

        }
    };

}(jQuery));
