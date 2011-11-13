<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id$
 *
 * @category Piwik
 * @package Piwik
 */

/**
 * Generates the data in the Open Flash Chart format, from the given data.
 *
 * @package Piwik
 * @subpackage Piwik_Visualization
 */
abstract class Piwik_Visualization_Chart implements Piwik_View_Interface
{
	
	// the data kept here conforms to the jqplot data layout
	// @see http://www.jqplot.com/docs/files/jqPlotOptions-txt.html
	protected $series = array();
	protected $data = array();
	protected $axes = array();
	protected $tooltip = array();
	protected $seriesColors = array('#000000');
	protected $seriesPicker = array();
	
	// other attributes (not directly used for jqplot)
	protected $maxValue;
	protected $yUnit = '';
	protected $displayPercentageInTooltip = true;
	protected $xSteps = 2;
	
	public function setAxisXLabels(&$xLabels)
	{
		$this->axes['xaxis']['ticks'] = &$xLabels;
	}

	public function setAxisXOnClick(&$onClick)
	{
		$this->axes['xaxis']['onclick'] = &$onClick;
	}
	
	public function setAxisYValues(&$values)
	{
		foreach ($values as $label => &$data)
		{
			$this->series[] = array(
				'label' => $label,
				'internalLabel' => $label
			);
			
			array_walk($data, create_function('&$v', '$v = (float)$v;'));
			$this->data[] = &$data;
		}
	}
	
	protected function addTooltipToValue($seriesIndex, $valueIndex, $tooltipTitle, $tooltipText)
	{
		$this->tooltip[$seriesIndex][$valueIndex] = array($tooltipTitle, $tooltipText);
	}

	public function setAxisYUnit($yUnit)
	{
		$yUnits = array();
		for ($i = 0; $i < count($this->data); $i++)
		{
			$yUnits[] = $yUnit;
		}
		$this->setAxisYUnits($yUnits);
	}

	public function setAxisYUnits($yUnits)
	{
		// generate an axis config for each unit
		$axesIds = array();
		// associate each series with the appropriate axis
		$seriesAxes = array();
		// units for tooltips
		$seriesUnits = array();
		foreach ($yUnits as $unit)
		{
			// handle axes ids: first y[]axis, then y[2]axis, y[3]axis...
			$nextAxisId = empty($axesIds) ?  '' : count($axesIds) + 1;
			
			$unit = $unit ? $unit : '';
			if (!isset($axesIds[$unit]))
			{
				$axesIds[$unit] = array('id' => $nextAxisId, 'unit' => $unit);
				$seriesAxes[] = 'y'.$nextAxisId.'axis';
			}
			else
			{
				// reuse existing axis
				$seriesAxes[] = 'y'.$axesIds[$unit]['id'].'axis';
			}
			$seriesUnits[] = $unit;
		}
		
		// generate jqplot axes config
		foreach ($axesIds as $axis) {
			$axisKey = 'y'.$axis['id'].'axis';
			$this->axes[$axisKey]['tickOptions']['formatString'] = '%s'.$axis['unit'];
		}
	
		$this->tooltip['yUnits'] = $seriesUnits;
	
		// add axis config to series
		foreach ($seriesAxes as $i => $axisName) {
			$this->series[$i]['yaxis'] = $axisName;
		}
	}
	
	public function setAxisYLabels($labels)
	{
		foreach ($this->series as &$series)
		{
			$label = $series['internalLabel'];
			if (isset($labels[$label]))
			{
				$series['label'] = $labels[$label];
			}
		}
	}
	
	public function setDisplayPercentageInTooltip($display)
	{
		$this->displayPercentageInTooltip = $display;
	}
	
	public function setXSteps($steps)
	{
		$this->xSteps = $steps;
	}
	
	public function setSelectabelColumns($selectableColumns)
	{
		$this->seriesPicker['selectableColumns'] = $selectableColumns;
	}

	public function render()
	{
		Piwik::overrideCacheControlHeaders();
		
		$data = array(
			'params' => array(
				'axes' => &$this->axes,
				'series' => &$this->series,
				'seriesColors' => &$this->seriesColors
			),
			'data' => &$this->data,
			'tooltip' => &$this->tooltip,
			'seriesPicker' => &$this->seriesPicker
		);
		
		return Piwik_Common::json_encode($data);
	}
	
	public function customizeChartProperties()
	{
		// x axis labels with steps
		if (isset($this->axes['xaxis']['ticks']))
		{
			foreach ($this->axes['xaxis']['ticks'] as $i => &$xLabel)
			{
				$this->axes['xaxis']['labels'][$i] = $xLabel;
				if (($i % $this->xSteps) != 0)
				{
					$xLabel = ' ';
				}
			}
		}
	}
	
}
