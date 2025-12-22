RAJARSHI DAS
15-Feb-2011.

Setup Memo
----------
1. Require PEAR package and extensions

	1. PEAR::MAIL
	2. PEAR::MAIL_MIME 
	3. PEAR::Net_SMTP

	# aptitude install php-pear
	# pear -h (to get Help)
	# pear install --alldeps Image_Graph (example)

	# pear install --alldeps MAIL
	(will also install Net_SMTP as a dependency)
	# pear install --alldeps MAIL_MIME
	(Look in the /usr/share/php folder - the packages are here)

2. Installing PERL Modules

	example:
	Installing Perl from CPAN
	------------------------- 
 	perl -MCPAN -e shell                    [as root]
	o conf prerequisites_policy ask
	install Mail::SpamAssassin
	quit