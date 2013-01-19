<?php

class DownloadView {
    
    public function doDownloadLinks() {
            $html = 
			'
            <div class="row">
                <div class="span12">
                    <div class="inner">
                        <h1>Downloads</h1>
                    </div>  
                    <div class="row">
                        <div class="span4">
                            <div class="inner">
                                <a href="https://github.com/downloads/jensevertsson/ProjectX/Snippt.exe">
                                    <img src="content/image/icons/win/icon_256.png" alt="Download Snippt for Windows" title="Download Snippt for Windows" />
                                </a>
                            </div>
                        </div>
                        
                        <div class="span4"> 
                            <div class="inner"> 	
        						<a href="https://github.com/downloads/jensevertsson/ProjectX/Snippt.dmg">
        							<img src="content/image/icons/mac/icon_256.png" alt="Download Snippt for Mac OS X" title="Download Snippt for Mac OS X" />
        						</a>
                            </div>
                        </div>
                        
                        <div class="span4">
                            <div class="inner">
        						<a href="https://github.com/downloads/jensevertsson/ProjectX/snippt-1.0-3-i686.els">
        							<img src="content/image/icons/enlisy/icon_256.png" alt="Download Snippt for Enlisy" title="Download Snippt for Enlisy" />
        						</a>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
			';
            return $html;
    }
}