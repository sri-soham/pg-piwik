<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id$
 *
 * @category Piwik_Plugins
 * @package Piwik_ImageGraph
 */


/**
 *
 * @package Piwik_ImageGraph
 */
class Piwik_ImageGraph_StaticGraph_HorizontalBar extends Piwik_ImageGraph_StaticGraph_GridGraph
{
	const INTERLEAVE = 0.30;
	const TRUNCATION_TEXT = '...';
	const PADDING_CHARS = ' ';
	const LEGEND_SQUARE_WIDTH = 11;
	const MIN_SPACE_BETWEEN_HORIZONTAL_VALUES = 5;
	const LOGO_MIN_RIGHT_MARGIN = 3;

	public function renderGraph()
	{
		// determine the maximum logo width & height
		$maxLogoWidth = 0;
		$maxLogoHeight = 0;
		$logoPathToSizes = array();
		foreach($this->ordinateLogos as $logoPath)
		{
			$absoluteLogoPath = self::getAbsoluteLogoPath($logoPath);
			if(file_exists($absoluteLogoPath))
			{
				$logoWidthHeight = self::getLogoWidthHeight($absoluteLogoPath);
				$logoWidth = $logoWidthHeight[self::WIDTH_KEY];
				$logoHeight = $logoWidthHeight[self::HEIGHT_KEY];
	
				$logoPathToSizes[$absoluteLogoPath] = $logoWidthHeight;
				if($logoWidth > $maxLogoWidth)
				{
					$maxLogoWidth = $logoWidth;
				}
	
				if($logoHeight > $maxLogoHeight)
				{
					$maxLogoHeight = $logoHeight;
				}
			}
		}

		// truncate report
		$graphHeight = $this->getGraphBottom() - $this->getGridTopMargin($horizontalGraph = true);
		$abscissaMaxWidthHeight = $this->maxWidthHeight($this->abscissaSerie);
		$abscissaMaxHeight = $abscissaMaxWidthHeight[self::HEIGHT_KEY];
		$minLineWidth = ($abscissaMaxHeight > $maxLogoHeight ? $abscissaMaxHeight : $maxLogoHeight) + self::MIN_SPACE_BETWEEN_HORIZONTAL_VALUES;
		$maxNumOfValues = floor($graphHeight / $minLineWidth);
		$abscissaSerieCount = count($this->abscissaSerie);
		
		if($maxNumOfValues < $abscissaSerieCount - 1)
		{
			$truncatedOrdinateSerie = array();
			$truncatedOrdinateLogos = array();
			$truncatedAbscissaSerie = array();

			$i = 0;
			for(; $i < $maxNumOfValues; $i++)
			{
				$truncatedOrdinateSerie[] = $this->ordinateSerie[$i];
				$truncatedOrdinateLogos[] = isset($this->ordinateLogos[$i]) ? $this->ordinateLogos[$i] : null;
				$truncatedAbscissaSerie[] = $this->abscissaSerie[$i];
			}

			$sumOfOthers = 0;
			for(; $i < $abscissaSerieCount; $i++)
			{
				$sumOfOthers += $this->ordinateSerie[$i];
			}
			$truncatedOrdinateSerie[] = $sumOfOthers;
			$truncatedAbscissaSerie[] = Piwik_Translate('General_Others');
			$this->ordinateSerie = $truncatedOrdinateSerie;
			$this->ordinateLogos = $truncatedOrdinateLogos;
			$this->abscissaSerie = $truncatedAbscissaSerie;
		}

		// blank characters are used to pad labels so the logo can be displayed
		$paddingText = '';
		$paddingWidth = 0;
		if($maxLogoWidth > 0)
		{
			while($paddingWidth < $maxLogoWidth + self::LOGO_MIN_RIGHT_MARGIN)
			{
				$paddingText .= self::PADDING_CHARS;
				$paddingTextWidthHeight = $this->getTextWidthHeight($paddingText);
				$paddingWidth = $paddingTextWidthHeight[self::WIDTH_KEY];
			}
		}

		// determine the maximum label width according to the minimum comfortable graph size
		$gridRightMargin = $this->getGridRightMargin($horizontalGraph = true);
		$minGraphSize = ($this->width - $gridRightMargin) / 2;
		
		$metricTitleWidthHeight = $this->getTextWidthHeight($this->metricTitle);
		$legendWidth = $metricTitleWidthHeight[self::WIDTH_KEY] + self::LEGEND_LEFT_MARGIN + self::LEGEND_SQUARE_WIDTH;
		if($this->showMetricTitle)
		{
			if($legendWidth > $minGraphSize)
			{
				$minGraphSize = $legendWidth;
			}
		}

		$gridLeftMarginWithoutLabels = $this->getGridLeftMargin($horizontalGraph = true, $withLabel = false);
		$labelWidthLimit =
				$this->width
				- $gridLeftMarginWithoutLabels
				- $gridRightMargin
				- $paddingWidth
				- $minGraphSize;

		// truncate labels if needed
		$truncationTextWidthHeight = $this->getTextWidthHeight(self::TRUNCATION_TEXT);
		$truncationTextWidth = $truncationTextWidthHeight[self::WIDTH_KEY];
		foreach($this->abscissaSerie as &$label)
		{
			$labelWidthHeight = $this->getTextWidthHeight($label);
			$labelWidth = $labelWidthHeight[self::WIDTH_KEY];
			if($labelWidth > $labelWidthLimit)
			{
				$averageCharWidth = $labelWidth / strlen($label);
				$charsToKeep = floor(($labelWidthLimit - $truncationTextWidth) / $averageCharWidth);
				$label = substr($label, 0, $charsToKeep) . self::TRUNCATION_TEXT;
			}
		}

		$gridLeftMarginBeforePadding = $this->getGridLeftMargin($horizontalGraph = true, $withLabel = true);

		// pad labels for logo space
		foreach($this->abscissaSerie as &$label)
		{
			$label .= $paddingText;
		}

		$this->initGridChart(
			$displayVerticalGridLines = false,
			$drawCircles = false,
			$horizontalGraph = true,
			$showTicks = false
		);

		$valueColor = $this->colors[self::VALUE_COLOR_KEY];
		$this->pImage->drawBarChart(
			array(
				'DisplayValues' => true,
				'Interleave' => self::INTERLEAVE,
				'DisplayR' => $valueColor['R'],
				'DisplayG' => $valueColor['G'],
				'DisplayB' => $valueColor['B'],
			)
		);

		// display icons
		$graphData = $this->pData->getData();
		$sizeOfOrdinateSerie = sizeof($this->ordinateSerie);
		$logoInterleave = $this->getGraphHeight(true) / $sizeOfOrdinateSerie;
		for($i = 0; $i < $sizeOfOrdinateSerie; $i++)
		{
			if(isset($this->ordinateLogos[$i]))
			{
				$logoPath = $this->ordinateLogos[$i];
				$absoluteLogoPath = self::getAbsoluteLogoPath($logoPath);
				
				
				if(isset($logoPathToSizes[$absoluteLogoPath]))
				{
					$logoWidthHeight = $logoPathToSizes[$absoluteLogoPath];
	
					$pathInfo = pathinfo($logoPath);
					$logoExtension = strtoupper($pathInfo['extension']);
					$drawingFunction = 'drawFrom' . $logoExtension;
	
					$logoYPosition =
							($logoInterleave * $i)
							+ $this->getGridTopMargin(true)
							+ $graphData['Axis'][1]['Margin']
							- $logoWidthHeight[self::HEIGHT_KEY] / 2
							+ 1;
	
					$this->pImage->$drawingFunction(
						$gridLeftMarginBeforePadding,
						$logoYPosition,
						$absoluteLogoPath
					);
				}
			}
		}
	}

	private static function getAbsoluteLogoPath($relativeLogoPath)
	{
		return PIWIK_INCLUDE_PATH . '/' . $relativeLogoPath;
	}

	private static function getLogoWidthHeight($logoPath)
	{
		$pathInfo = getimagesize($logoPath);
		return array(
			self::WIDTH_KEY => $pathInfo[0],
			self::HEIGHT_KEY => $pathInfo[1]
		);
	}
}
