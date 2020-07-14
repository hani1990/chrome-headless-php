# chrome-headless-php

chrome无头浏览器php采集
无头浏览器的好处在于可以解析动态加载的网站，以及不需打开浏览器，不影响我们的其他操作，可以把代码放到服务器上跑，部署云服务岂不更香。

Mac下在~/.bashrc下进行如下配置:

alias chrome="/Applications/Google\ Chrome.app/Contents/MacOS/Google\ Chrome"

alias chrome-canary="/Applications/Google\ Chrome\ Canary.app/Contents/MacOS/Google\ Chrome\ Canary"

alias chromium="/Applications/Chromium.app/Contents/MacOS/Chromium"

alias start_chrome_server="chrome --disable-gpu --remote-debugging-port=9222"

export CHROME_PATH="/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"

保存配置

$ source ~/.bashrc

官方库 https://github.com/chrome-php/headless-chromium-php

test.php 里面的代码是通过chrome 无头浏览器实现百度关键词搜索列表页采集。

采集的列表页保存在 runtime/baidu 这个目录下。

自行解析列表页。

可以用同样的方法来采集谷歌、bing、搜狗等搜索引擎关键词采集。
