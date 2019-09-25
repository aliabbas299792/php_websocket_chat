# PHP Websocket Chat

A website implementing Ratchet and ReactPHP to make a series of live chats

All you need to do initially is go to include/db.info.php and add your MySQL credidentials, and then go import the SQL file in this repo.
Then go to chat-server/bin/ and run in command line `php chat-server.php` to get the chat going and your set. 

Also for the signing up bit (which you need to do to get even a single users) you'll need to setup `ajax_reqs/signup_processing.php` to actually work, i.e get your SMTP relay in there, your account which you'll be sending from etc, and then you'll be able to sign up and validate it.

*Then*, you're properly set.

-----------------------
PHP Mailer - Copyright (C) 1991, 1999 Free Software Foundation, Inc - GNU Lesser General Public License v2.1 <br>
RatchetPHP - Copyright (c) 2011-2017 Chris Boden - MIT License
