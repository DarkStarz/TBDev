        jssor_1_slider_init = function() {

            var jssor_1_options = {
              $AutoPlay: true,
              $Idle: 0,
			  //Left - Right
			  $AutoPlaySteps: 4,
              // right - left
			  //$AutoPlaySteps: -1,
              $SlideDuration: 2500,
              $SlideEasing: $Jease$.$Linear,
              $PauseOnHover: 4,
              $SlideWidth: 120,
              $Cols: 9
            };

            var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);
            
            /*responsive code begin*/
            /*you can remove responsive code if you don't want the slider scales while window resizing*/
            function ScaleSlider() {
                var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
                if (refSize) {
                    refSize = Math.min(refSize, 809);
                    jssor_1_slider.$ScaleWidth(refSize);
                }
                else {
                    window.setTimeout(ScaleSlider, 30);
                }
            }
            ScaleSlider();
            $Jssor$.$AddEvent(window, "load", ScaleSlider);
            $Jssor$.$AddEvent(window, "resize", ScaleSlider);
            $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
            /*responsive code end*/
        };