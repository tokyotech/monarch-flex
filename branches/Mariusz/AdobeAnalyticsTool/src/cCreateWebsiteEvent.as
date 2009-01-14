package
{
	import flash.events.Event;

	public class cCreateWebsiteEvent extends Event
	{
		public var CREATE_WEBSITE:String = "createAccount";
		public var CANCEL:String = "cancel";
		
		public var WEBSITE_TYPE_FORUM:String = "forum";
		public var WEBSITE_TYPE_BLOG:String = "blog";
		public var WEBSITE_TYPE_NEWS:String = "news";
		
		public function cCreateWebsiteEvent(type:String, bubbles:Boolean=false, cancelable:Boolean=false)
		{
			super(type, bubbles, cancelable);
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////
		//
		//									DATA MEMBERS
		//
		///////////////////////////////////////////////////////////////////////////////////////////
		
		public var mType:String = "";
		public var mWebsiteType:String = "";
		public var mWebsiteName:String = "";
		public var mWebsiteURL:String = "";
	}
}