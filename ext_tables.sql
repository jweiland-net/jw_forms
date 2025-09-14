#
# Table structure for table 'tx_jwforms_domain_model_form'
#
CREATE TABLE tx_jwforms_domain_model_form
(
	title       varchar(255) DEFAULT '' NOT NULL,
	file        text,
	url_to_file varchar(255) DEFAULT '' NOT NULL,
	tags        varchar(255) DEFAULT '' NOT NULL,
	categories  int(11) DEFAULT '0' NOT NULL
);
