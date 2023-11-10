<?php 

class HSReportHelper
{
	private $format;
	private $file;
	private $fileName;

	function __construct(&$file, $format)
	{
		$this->file = &$file;
		$this->format = $format;
		$this->fileName = sprintf('report_%d', time());
	}

	public function setFileName($fileName)
	{
		$this->fileName = $fileName;
	}

	public function httpHeaders()
	{
		// content type
		if (in_array($this->format, ['html','xls'])) {
			if ($this->format == 'xls') {
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			} else {
				header('Content-Type: text/html');
			}
		} elseif ($this->format == 'csv') {
			header('Content-Type: text/plain');
		}
		// cache control
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		// force download file
		if (in_array($this->format, ['csv','xls'])) {
			header(
				sprintf(
					'Content-Disposition: attachment;filename="%s.%s"',
					$this->fileName, $this->format
				)
			);
		}
	}

	public function writeHeader($colNames)
	{
		if (in_array($this->format, ['html','xls'])) {
			$html = '';
			$html .= '<!DOCTYPE html><html><head><meta charset="utf-8">';
			$html .= '<title>'.htmlentities($this->fileName).'</title>';
			$html .= '</head><body>';
			$html .= '<table border="1" style="border-collapse:collapse" cellpadding="5">';
			$html .= '<thead><tr>';
			foreach ($colNames as $name) {
				$html .= '<th>'.htmlentities($name).'</th>';
			}
			$html .= '</tr></thead><tbody>';
			$html .= "\n";
			fwrite($this->file, $html);
		} elseif ($this->format == 'csv') {
			fputcsv($this->file, $colNames);
		}
	}

	public function writeRow($row)
	{
		if (in_array($this->format, ['html','xls'])) {
			$html = '';
			$html .= '<tr>';
			foreach ($row as $val) {
				$html .= '<td>'.htmlentities($val).'</td>';
			}
			$html .= '</tr>';
			$html .= "\n";
			fwrite($this->file, $html);
		} elseif ($this->format == 'csv') {
			fputcsv($this->file, $row);
		}
	}

	public function writeFooter()
	{
		if (in_array($this->format, ['html','xls'])) {
			$html = '';
			$html .= '</tbody></table></body></html>';
			fwrite($this->file, $html);
		}
	}
}
?>
