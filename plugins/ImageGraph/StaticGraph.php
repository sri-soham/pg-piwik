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

require_once PIWIK_INCLUDE_PATH . "/libs/pChart2.1.3/class/pDraw.class.php";
require_once PIWIK_INCLUDE_PATH . "/libs/pChart2.1.3/class/pImage.class.php";
require_once PIWIK_INCLUDE_PATH . "/libs/pChart2.1.3/class/pData.class.php";

/**
 * The Piwik_ImageGraph_StaticGraph abstract class is used as a base class for different types of static graphs.
 *
 * @package Piwik_ImageGraph
 * @subpackage Piwik_ImageGraph_StaticGraph
 */
abstract class Piwik_ImageGraph_StaticGraph
{
	const GRAPH_TYPE_BASIC_LINE = "evolution";
	const GRAPH_TYPE_VERTICAL_BAR = "verticalBar";
	const GRAPH_TYPE_HORIZONTAL_BAR = "horizontalBar";
	const GRAPH_TYPE_3D_PIE = "3dPie";
	const GRAPH_TYPE_BASIC_PIE = "pie";

	static private $availableStaticGraphTypes = array(
		self::GRAPH_TYPE_BASIC_LINE => 'Piwik_ImageGraph_StaticGraph_Evolution',
		self::GRAPH_TYPE_VERTICAL_BAR => 'Piwik_ImageGraph_StaticGraph_VerticalBar',
		self::GRAPH_TYPE_HORIZONTAL_BAR => 'Piwik_ImageGraph_StaticGraph_HorizontalBar',
		self::GRAPH_TYPE_BASIC_PIE => 'Piwik_ImageGraph_StaticGraph_Pie',
		self::GRAPH_TYPE_3D_PIE => 'Piwik_ImageGraph_StaticGraph_3DPie',
	);

	const ABSCISSA_SERIE_NAME = 'ABSCISSA';
	const WIDTH_KEY = 'WIDTH';
	const HEIGHT_KEY = 'HEIGHT';

	private $aliasedGraph;

	protected $pImage;
	protected $pData;
	protected $metricTitle;
	protected $showMetricTitle;
	protected $abscissaSerie;
	protected $ordinateSerie;
	protected $ordinateLogos;
	protected $colors;
	protected $font;
	protected $fontSize;
	protected $width;
	protected $height;

	abstract protected function getDefaultColors();

	abstract public function renderGraph();

	/**
	 * Return the StaticGraph according to the static graph type $graphType
	 *
	 * @throws exception If the static graph type is unknown
	 * @param string $graphType
	 * @return Piwik_ImageGraph_StaticGraph
	 */
	public static function factory($graphType)
	{
		if (isset(self::$availableStaticGraphTypes[$graphType]))
		{

			$className = self::$availableStaticGraphTypes[$graphType];
			Piwik_Loader::loadClass($className);
			return new $className;
		}
		else
		{
			throw new Exception(
				Piwik_TranslateException(
					'General_ExceptionInvalidStaticGraphType',
					array($graphType, implode(', ', self::getAvailableStaticGraphTypes()))
				)
			);
		}
	}

	public static function getAvailableStaticGraphTypes()
	{
		return array_keys(self::$availableStaticGraphTypes);
	}

	/**
	 * Save rendering to disk
	 *
	 * @param string $filename without path
	 * @return string path of file
	 */
	public function sendToDisk($filename)
	{
		$filePath = self::getOutputPath($filename);
		$this->pImage->Render($filePath);
		return $filePath;
	}

	/**
	 * @return rendered static graph
	 */
	public function getRenderedImage()
	{
		return $this->pImage->Picture;
	}

	/**
	 * Output rendering to browser
	 */
	public function sendToBrowser()
	{
		$this->pImage->stroke();
	}

	public function setWidth($width)
	{
		$this->width = $width;
	}

	public function setHeight($height)
	{
		$this->height = $height;
	}

	public function setFontSize($fontSize)
	{
		$this->fontSize = $fontSize;
	}

	public function setFont($font)
	{
		$this->font = $font;
	}

	public function setOrdinateSerie($ordinateSerie)
	{
		$this->ordinateSerie = $ordinateSerie;
	}

	public function setOrdinateLogos($ordinateLogos)
	{
		$this->ordinateLogos = $ordinateLogos;
	}

	public function setAbscissaSerie($abscissaSerie)
	{
		$this->abscissaSerie = $abscissaSerie;
	}

	public function setShowMetricTitle($showMetricTitle)
	{
		$this->showMetricTitle = $showMetricTitle;
	}

	public function setMetricTitle($metricTitle)
	{
		$this->metricTitle = $metricTitle;
	}

	public function setAliasedGraph($aliasedGraph)
	{
		$this->aliasedGraph = $aliasedGraph;
	}

	public function setColors($colors)
	{
		$i = 0;
		foreach($this->getDefaultColors() as $colorKey => $defaultColor)
		{
			if(isset($colors[$i]) && $this->hex2rgb($colors[$i]))
			{
				$hexColor = $colors[$i];
			}
			else
			{
				$hexColor = $defaultColor;
			}

			$this->colors[$colorKey] = $this->hex2rgb($hexColor);
			$i++;
		}
	}

	/**
	 * Return $filename with temp directory and delete file
	 *
	 * @static
	 * @param  $filename
	 * @return string path of file in temp directory
	 */
	protected static function getOutputPath($filename)
	{
		$outputFilename = PIWIK_USER_PATH . '/tmp/assets/' . $filename;
		@chmod($outputFilename, 0600);
		@unlink($outputFilename);
		return $outputFilename;
	}

	protected function initpData()
	{
		$this->pData = new pData();

		$this->pData->addPoints($this->ordinateSerie, $this->metricTitle);
		$this->pData->setAxisName(0, '', $this->metricTitle);

		$this->pData->addPoints($this->abscissaSerie, self::ABSCISSA_SERIE_NAME);
		$this->pData->setAbscissa(self::ABSCISSA_SERIE_NAME);
	}

	protected function initpImage()
	{
		$this->pImage = new pImage($this->width, $this->height, $this->pData);
		$this->pImage->Antialias = $this->aliasedGraph;

		$this->pImage->setFontProperties(
			array(
				 "FontName" => $this->font,
				 "FontSize" => $this->fontSize
			)
		);
	}

	protected function getTextWidthHeight($text)
	{
		$position = imageftbbox($this->fontSize, 0, $this->font, $text);

		return array(
			self::WIDTH_KEY => ($position[0]) + abs($position[2]),
			self::HEIGHT_KEY => ($position[1]) + abs($position[5])
		);
	}

	protected function maxWidthHeight($values)
	{
		$maxWidth = 0;
		$maxHeight = 0;
		foreach($values as $value)
		{
			$valueWidthHeight = $this->getTextWidthHeight($value);
			$valueWidth= $valueWidthHeight[self::WIDTH_KEY];
			$valueHeight= $valueWidthHeight[self::HEIGHT_KEY];

			if($valueWidth > $maxWidth)
			{
				$maxWidth = $valueWidth;
			}

			if($valueHeight > $maxHeight)
			{
				$maxHeight = $valueHeight;
			}
		}

		return array(
			self::WIDTH_KEY => $maxWidth,
			self::HEIGHT_KEY => $maxHeight
		);
	}

	private static function hex2rgb($hexColor)
	{
		if(preg_match('/([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/', $hexColor, $matches))
		{
			return array(
				'R' => hexdec($matches[1]),
				'G' => hexdec($matches[2]),
				'B' => hexdec($matches[3])
			);
		}
		else
		{
			return false;
		}
	}
}