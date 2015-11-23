<?php
/**
 * This file will be responsible for loading which environment to point on.
 */
class Environment
{
	/**
	 * Developer's Own Machine Settings
	 */
	const LOCALHOST			= 'localhost';

	/**
	 * Public Facing Server
	 */ 
	const DEVELOPMENT		= 'development';
	const STAGING			= 'staging';
	const PRODUCTION		= 'production';
}