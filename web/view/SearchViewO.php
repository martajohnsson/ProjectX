<?php

class SearchView {
    
    public function doSearchForm() {
            $html = '<div class="search">
                            <img src="content/image/snippet-logo.png" alt="Snippt" />
                            <input type="text" name="q" class="search_input" />
                    </div>
                    <div id="result"></div>';
            $html .= '<script type="text/javascript">
                        $(document).ready(function() {
                            var timer = null;
                            
                            $(".search_input").keyup(function() {
                                clearTimeout(timer);
                                timer = setTimeout(search, 500);
                            });
                            
                            function search() {
                                $("#result").html("");
                                var search_input = $(".search_input").val();
                                var query = encodeURIComponent(search_input);
                                
                                if (query.length > 2) {
                                    $("#result").html("<img src=\"content/image/ajax-loader.gif\" />");
                                    $.ajax({
                                        type: "GET",
                                        url: "model/SearchSnippet.php",
                                        data: {
                                            "query": query
                                        },
                                        dataType: "html",
                                        success: function(data) {
                                            $("#result").html(data);
                                        }
                                    });
                                }
                            }
                        });
                        </script>';
            return $html;
    }
}