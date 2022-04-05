<?php

#namespace WilokeTest;

class AQuery
{
	/**
	 * @var array $aRawArgs
	 */
	private $aRawArgs;

	/**
	 * @var IQueryBuilder
	 */
	private $oQueryBuilder;

	/**
	 * @var array $aArgs
	 */
	private $aArgs;

	/**
	 * @var IResponse
	 */
	private $oResponseHanlder;
	/**
	 * @var IQuery
	 */
	private $oQueryHandler;

	public function setQueryBuilderHandler(IQueryBuilder $oQueryBuilder): AQuery
	{
		$this->oQueryBuilder = $oQueryBuilder;

		return $this;
	}

	public function setResponseHandler(IResponse $oResponseHandler): AQuery
	{
		$this->oResponseHanlder = $oResponseHandler;
		return $this;
	}

	public function setQueryHandler(IQuery $oQueryHandler): AQuery
	{
		$this->oQueryHandler = $oQueryHandler;
		return $this;
	}

	private function parseArgs(): AQuery
	{
		$this->aArgs = $this->oQueryBuilder->setRawArgs($aArgs)->parseArgs()->getArgs();

		return $this;
	}

	public function setRawArgs($aRawArgs): AQuery
	{
		$aArgs = $aRawArgs;
		return $this;
	}

	public function getMaxPages()
	{
		return $this->oQueryHandler->getQuery()->max_num_pages;
	}

	public function getMaxPosts()
	{
		return $this->oQueryHandler->getQuery()->found_posts;
	}
}
