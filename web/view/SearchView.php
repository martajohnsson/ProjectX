<?php

class SearchView {
    
    public function doSearchForm() {
                    
            $html = '<div class ="row">
                        <div class="span12 search pagination-centered">
                            <img src="content/image/snippet-logo.png" alt="Snippt" />
                        </div>
                    </div>
                    <div class ="row">
                        <div class="span12 pagination-centered">
                            <input type="text" name="q" id="search_input" class="input-xlarge uneditable-input" />
                        </div>
                    </div>
                    <div class ="row">
                        <div class="span12 pagination-centered" id="result"></div>
                    </div>';
                            
            $html .= '<script type="text/javascript">
                        $(document).ready(function() {
                            var timer = null;
                            
                            $("#search_input").keyup(function() {
                                clearTimeout(timer);
                                timer = setTimeout(search, 500);
                            });
                            
                            function search() {
                                $("#result").html("");
                                var search_input = $("#search_input").val();
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