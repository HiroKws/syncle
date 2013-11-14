Syncle
======

Laravel 4 Arisan deploy command by using execute a command line.

This is beta version, not tested well yet.

~~~~
php artisan syncle
~~~~

Then deploy the project that installed this package by commands on a config setting .

You can also change command name by setting favoret name on config file.

(But...in honesty...the best way is that just put a named shell script on each project root, and execute when deploy the project. This is simpler and easiest to maintain... :D This package's main purpose is to lean how to make command and shell script handling, error output and so on. If you use this Artisan command, you can see colorized message on some output. This is a beautiful benefit. :D )

#### Install

Run :

~~~~
composer require "hirokws/syncle:master-dev"
~~~~

Or, add follwing code into require section of root composer.json file.

~~~~
"hirokws/syncle" : "master-dev"
~~~~

Then, add service provider into app/config/app.php.

~~~
'Syncle\SyncleServiceProvider',
~~~

Next, publish config file into your app/config folder.

~~~~
php artisan config:publish hirokws/syncle
~~~~

#### Set Up

Please open app/config/packages/hirokws/syncle/config.php.

And change setting as you like.

* CommandName : Command name to deploy. Default "syncle".
* MessageLang : Display language. Only 'en' and 'ja' are available now.
* DeployMethod.default : rsync command to deploy. This default command will be used when no --by option to specify deploy method. In the command, ':root' will be replaced to project root path without ends '/'. As same as, ':projectRoot' will be replaced to project root. This is for a project on workbench direcotry. Anyway, you must set rsync command propery.
* DeployMethos.git : An example of git commands. To use this command setting, run command with '--by git'. You can put any item names to deploy methods. Just it will be used to identify. ':message' will be replaced to message specified by '--message' option.
* DefaultGitMessage : Dafault string that will be replaced to ':message'.

#### Run

**Deploy by 'default' setting :**

~~~~
php artisan syncle
~~~~

**Deploy by 'git' setting :**

~~~~
php artisan syncle --by git --message "Initial Commit"
php artisan syncle -b git -m "Initial Commit"
~~~~

Of course, if you project will deployed by git mainly, so set it as 'default'. Then command become shorter.

If Ommited --message, DefaultGitMessage item on config file will be used. (If there is ':message' in DeployMethos.git command(s).

**Show transferred files list :**

On Linux, rsync display transfering file names, and inhibited normal mode. If you want to see all tranferred files, use verbose mode.

~~~~
php artisan syncle -v
php artisan syncle -vv
php artisan syncle -vvv
php artisan syncle --verbose 1
php artisan syncle --verbose 2
php artisan syncle --verbose 3
~~~~

Every above options, works as same. They showed transferred files list.

**Log output:**

If you want to log output, so use log option.

~~~~
php artisan syncle --log
~~~~

### Why

Why I made this. The reason is sometime I have confused 'How I deploy this project?'.

Mainly, I used rsync. When I push something to Github, so used git. For some shared hosting by FTP.

Normally I made upload bash alias for each project. But already too many to remember them. Oh I'm getting old...

This 10 days, I read/translated [Laravel 4 Cookbook](https://leanpub.com/laravel4cookbook). And I got an idea to make a deploy command as Artisan CLI. If so, I just kick deploy command on an top of root folder. It's nice ! ( But...as I mentioned, the best way is just making simple same upload script on each project top folder..., I think. ;) )

And Yesterday, I had translated, and released Japanese version. Then started to development for myself.

Have you read this book? If not, read it. You may also get some ideas to improve your workflow. Not only knowledge of Laravel 4.

You can read those tutorials on Midium, but this is cheeper. I suggest that you get a copy.

### License

The whole under LIT license. ( My bad English also. :D ) ( Pretty bugs also. :D :D )
