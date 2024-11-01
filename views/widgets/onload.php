<script type="text/javascript" language="Javascript">
    
    //Function to debug SiteApps Widget
    function consoleWrite(msg, value)
    {
<?php if(isset($debug) && $debug) : ?>
        try {
           console.log(msg, value);
        } catch(e) {
        }
<?php endif; ?>
    }
        
    function toArray(obj) {
        var arr = [];
        for (var key in obj) {
            arr[key] = obj[key];
        }
        consoleWrite('config: ' , arr);
        return arr;
    }        
    
    function mustRunWidget (segments, expression) {
        // expression must be a logical expression of bucket values. eg: (A or B) and C
        
        var userSegments = segments
          , expressionForEval = expression.replace(/\s+and\s+/ig, '&&').replace(/\s+or\s+/ig, '||').replace(/not/ig, '!')
          , regex = null
          , i = 0;

        var tokensMy = expression.replace(/\(/g, "").replace(/\)/g, "").replace(/\s+and\s+/ig, ' ').replace(/\s+or\s+/ig, ' ').replace(/not/ig, '');
        var allSegments = tokensMy.trim().split(' ');

        for(var s in allSegments) {
            var replacement = 'false';
            var seg = allSegments[s];
            if (userSegments.indexOf(seg) != -1) {
                replacement = 'true';
            }
            regex = new RegExp(allSegments[s], 'ig');
            expressionForEval = expressionForEval.replace(regex, replacement);
//            consoleWrite('expression with : '+ allSegments[s], expressionForEval);
        }
        consoleWrite('expression: ' , expressionForEval);
        
        try {
            return eval(expressionForEval);
        } catch (err) {
            consoleWrite('error: ' , err);
            return false;
        }

        return true;
    }
    
    var widgetsToShow = toArray(<?php print $widgetsToShow; ?>),
    widgetsToHide = toArray(<?php print $widgetsToHide; ?>),
    expressionsToShow = toArray(<?php print $expressionList; ?>);
        
        //toArray({"recent-posts-3": "alow", "recent-posts-2": "default_new_visitor", "recent-comments-2": "mobile or default_new_visitor", "archives-2": "((marcelio or errado) and (teste or default_new_visitor)) or default_new_visitor"});
    

    function changeDisplay(widgets, display) {
        consoleWrite('widgets display to ' + display + ': ' , widgets);
        for (var i in widgets) {
            var widgetId = widgets[i];
            var e;
            while (e = document.getElementById(widgetId)) {
                e.style.display = display;
                e.id = widgetId + '-modified';                
            }
        }
    }

    function applyingExpressions(segments) {
        for (var ex in expressionsToShow) {
            var expression = expressionsToShow[ex];
            consoleWrite('expression pure: ', expression);
            consoleWrite('widget: ', ex);
            var show = mustRunWidget(segments, expression);
            if (show) {
                changeDisplay([ex], "block");
            }
        }
    }
    

    function applyingSegmentsConf(segments) {
        for (var b in segments) {
            var segment = segments[b];
            consoleWrite('segment: ', segment);
            if (segment in widgetsToHide) {
                changeDisplay(widgetsToHide[segment], 'none');
            }
            if (segment in widgetsToShow) {
                changeDisplay(widgetsToShow[segment], 'block');
            }
        }
    }
    
    function getBuckets () {
        var times = 50;
        var interval = setInterval(function () {
            if (times === 0) {
                clearInterval(interval); 
                return;
            }    
            consoleWrite('times: ', times);
            try{
                var segments = $SA.getAllUserBuckets();
                if(segments.length === 0) {
                    return;
                }
                clearInterval(interval); 
                consoleWrite('segments: ', segments);
                applyingSegmentsConf(segments);
                applyingExpressions(segments);
            } catch(e) {
                times -= 1;    
            }
        }, 200);
    }
    
    getBuckets();
</script>
