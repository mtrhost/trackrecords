Html Webpack Plugin:<pre>
  Error: Child compilation failed:
  Entry module not found: Error: Can't resolve 'D:\OpenServer\OpenServer\domains\rrr.loc\index.html' in 'D:\OpenServer\OpenServer\domains\rrr.loc':
  Error: Can't resolve 'D:\OpenServer\OpenServer\domains\rrr.loc\index.html' in 'D:\OpenServer\OpenServer\domains\rrr.loc'
  
  - compiler.js:76 
    [rrr.loc]/[html-webpack-plugin]/lib/compiler.js:76:16
  
  - Compiler.js:300 compile
    [rrr.loc]/[webpack]/lib/Compiler.js:300:11
  
  - Compiler.js:510 applyPluginsAsync.err
    [rrr.loc]/[webpack]/lib/Compiler.js:510:14
  
  - Tapable.js:202 next
    [rrr.loc]/[tapable]/lib/Tapable.js:202:11
  
  - CachePlugin.js:78 Compiler.<anonymous>
    [rrr.loc]/[webpack]/lib/CachePlugin.js:78:5
  
  - Tapable.js:206 Compiler.applyPluginsAsyncSeries
    [rrr.loc]/[tapable]/lib/Tapable.js:206:13
  
  - Compiler.js:507 compilation.seal.err
    [rrr.loc]/[webpack]/lib/Compiler.js:507:11
  
  - Tapable.js:195 Compilation.applyPluginsAsyncSeries
    [rrr.loc]/[tapable]/lib/Tapable.js:195:46
  
  - Compilation.js:683 applyPluginsAsync.err
    [rrr.loc]/[webpack]/lib/Compilation.js:683:19
  
  - Tapable.js:195 Compilation.applyPluginsAsyncSeries
    [rrr.loc]/[tapable]/lib/Tapable.js:195:46
  
  - Compilation.js:674 applyPluginsAsync.err
    [rrr.loc]/[webpack]/lib/Compilation.js:674:11
  
  - Tapable.js:195 Compilation.applyPluginsAsyncSeries
    [rrr.loc]/[tapable]/lib/Tapable.js:195:46
  
  - Compilation.js:669 applyPluginsAsync.err
    [rrr.loc]/[webpack]/lib/Compilation.js:669:10
  
  - Tapable.js:195 Compilation.applyPluginsAsyncSeries
    [rrr.loc]/[tapable]/lib/Tapable.js:195:46
  
  - Compilation.js:665 applyPluginsAsyncSeries
    [rrr.loc]/[webpack]/lib/Compilation.js:665:9
  
  - Tapable.js:195 Compilation.applyPluginsAsyncSeries
    [rrr.loc]/[tapable]/lib/Tapable.js:195:46
  
  - Compilation.js:608 Compilation.seal
    [rrr.loc]/[webpack]/lib/Compilation.js:608:8
  
  - Compiler.js:504 applyPluginsParallel.err
    [rrr.loc]/[webpack]/lib/Compiler.js:504:17
  
  - Tapable.js:289 
    [rrr.loc]/[tapable]/lib/Tapable.js:289:11
  
  - Compilation.js:511 _addModuleChain
    [rrr.loc]/[webpack]/lib/Compilation.js:511:11
  
  - Compilation.js:394 errorAndCallback.bail
    [rrr.loc]/[webpack]/lib/Compilation.js:394:4
  
  - Compilation.js:417 moduleFactory.create
    [rrr.loc]/[webpack]/lib/Compilation.js:417:13
  
  - NormalModuleFactory.js:237 factory
    [rrr.loc]/[webpack]/lib/NormalModuleFactory.js:237:20
  
  - NormalModuleFactory.js:60 resolver
    [rrr.loc]/[webpack]/lib/NormalModuleFactory.js:60:20
  
  - NormalModuleFactory.js:127 asyncLib.parallel
    [rrr.loc]/[webpack]/lib/NormalModuleFactory.js:127:20
  
  - async.js:3888 
    [rrr.loc]/[async]/dist/async.js:3888:9
  
  - async.js:473 
    [rrr.loc]/[async]/dist/async.js:473:16
  
  - async.js:1062 iteratorCallback
    [rrr.loc]/[async]/dist/async.js:1062:13
  
  - async.js:969 
    [rrr.loc]/[async]/dist/async.js:969:16
  
  - async.js:3885 
    [rrr.loc]/[async]/dist/async.js:3885:13
  
  - From previous event:
  
  - compiler.js:69 Object.compileTemplate
    [rrr.loc]/[html-webpack-plugin]/lib/compiler.js:69:10
  
  - index.js:47 Compiler.<anonymous>
    [rrr.loc]/[html-webpack-plugin]/index.js:47:40
  
  - Tapable.js:293 Compiler.applyPluginsParallel
    [rrr.loc]/[tapable]/lib/Tapable.js:293:14
  
  - Compiler.js:499 applyPluginsAsync.err
    [rrr.loc]/[webpack]/lib/Compiler.js:499:9
  
  - Tapable.js:195 Compiler.applyPluginsAsyncSeries
    [rrr.loc]/[tapable]/lib/Tapable.js:195:46
  
  - Compiler.js:492 Compiler.compile
    [rrr.loc]/[webpack]/lib/Compiler.js:492:8
  
  - Compiler.js:83 compiler.applyPluginsAsync.err
    [rrr.loc]/[webpack]/lib/Compiler.js:83:18
  
  - Tapable.js:202 next
    [rrr.loc]/[tapable]/lib/Tapable.js:202:11
  
  - CachePlugin.js:48 Compiler.compiler.plugin
    [rrr.loc]/[webpack]/lib/CachePlugin.js:48:5
  
  - Tapable.js:206 Compiler.applyPluginsAsyncSeries
    [rrr.loc]/[tapable]/lib/Tapable.js:206:13
  
  - Compiler.js:48 Watching._go
    [rrr.loc]/[webpack]/lib/Compiler.js:48:17
  
  - Compiler.js:40 Watching.compiler.readRecords.err
    [rrr.loc]/[webpack]/lib/Compiler.js:40:9
  
  - Compiler.js:391 Compiler.readRecords
    [rrr.loc]/[webpack]/lib/Compiler.js:391:11
  
  - Compiler.js:37 new Watching
    [rrr.loc]/[webpack]/lib/Compiler.js:37:17
  
  - Compiler.js:222 Compiler.watch
    [rrr.loc]/[webpack]/lib/Compiler.js:222:20
  
  - webpack.js:390 processOptions
    [rrr.loc]/[webpack]/bin/webpack.js:390:13
  
  - webpack.js:397 yargs.parse
    [rrr.loc]/[webpack]/bin/webpack.js:397:2
  
  - yargs.js:533 Object.Yargs.self.parse
    [rrr.loc]/[yargs]/yargs.js:533:18
  
  - webpack.js:152 Object.<anonymous>
    [rrr.loc]/[webpack]/bin/webpack.js:152:7
  
  - bootstrap_node.js:191 startup
    bootstrap_node.js:191:16
  
</anonymous></anonymous></anonymous></pre><script type=text/javascript src=\assets\js\main.js></script>