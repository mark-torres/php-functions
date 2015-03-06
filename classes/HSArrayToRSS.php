<?php
/**
* HSoft Array to RSS by Mark Torres <mark.torres.mets@gmail.com>
*/
class HSArrayToRSS
{
	private $source; // source array
	private $feed; // local SimpleXMLElement
	function __construct($data)
	{
		$this->source = $data;
	}
	
	public function getFeed()
	{
		$this->feed = new SimpleXMLElement("<rss/>");
		$this->feed->addAttribute("version", "2.0");
		// WRITE FEED
		$channel = $this->feed->addChild("channel");
		// Required Elements First
		$title = isset($this->source['title'])?$this->source['title']:"Dummy title";
		$link  = isset($this->source['link'])?$this->source['link']:"http://google.com";
		$descr = isset($this->source['description'])?$this->source['description']:"Dummy description.";
		$channel->addChild("title", htmlentities($title));
		$channel->addChild("link", $link);
		$channel->addChild("description", htmlentities($descr));
		// Items
		if(array_key_exists('items', $this->source) && is_array($this->source['items']))
		{
			foreach($this->source['items'] as $i => $item)
			{
				$article = $channel->addChild("item");
				// required elements
				$art_title = isset($item['title']) ? $item['title'] : "Dummy title for item $i.";
				$art_link  = isset($item['link']) ? $item['link'] : "http://localhost/dummy-link-for-item-{$i}.html";
				$art_descr = isset($item['title']) ? $item['title'] : "Dummy description for item $i.";
				$article->addChild("title", htmlentities($item['title']));
				$article->addChild("link", $item['link']);
				$article->addChild("description", htmlentities($item['description']));
				// optional elements - reference http://www.w3schools.com/rss/rss_item.asp
				// GUID: can be a permalink
				if(array_key_exists("guid", $item))
				{
					$guid = $article->addChild("guid", $item['guid']);
					if(preg_match("/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/", $item['guid']))
					{
						$guid->addAttribute("isPermaLink", "false");
					}
				}
				// author
				if(array_key_exists("author", $item))
				{
					$author = trim($item['author']);
					if(preg_match("/([\w|.|-]*@\w*\.[\w|.]*)\s+\((.+)\)/i", $author, $matches))
					{
						$author = "{$matches[1]} ({$matches[2]})";
						$article->addChild("author", $author);
					}
				}
				// comments
				if(array_key_exists("comments", $item))
				{
					$article->addChild("comments", $item['comments']);
				}
				// enclosure
				if(array_key_exists("enclosure", $item) && is_array($item['enclosure']))
				{
					$media = $article->addChild("enclosure");
					// required attributes for media files
					$media_url    = isset($item['enclosure']['url']) ? $item['enclosure']['url'] : "http://localhost/music.mp3";
					$media_length = isset($item['enclosure']['length']) ? $item['enclosure']['length'] : "234234";
					$media_type   = isset($item['enclosure']['type']) ? $item['enclosure']['type'] : "audio/mpeg";
					$media->addAttribute("url", $media_url);
					$media->addAttribute("length", $media_length);
					$media->addAttribute("type", $media_type);
				}
			}
		}
		// return XML
		return $this->feed->asXML();
	}// end of 'getFeed' function -------------------------
		
}// end of class HSArrayToRSS = = = = = = = = = = = = = = = =
