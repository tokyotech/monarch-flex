package
{
	import flash.events.Event;

	public class cStatOptionSelectedEvent extends Event
	{
		public function cStatOptionSelectedEvent(type:String, bubbles:Boolean=false, cancelable:Boolean=false)
		{
			super(type, bubbles, cancelable);
		}
		
		
		///////////////////////////////////////////////////////////////////////////////////
		//
		//						   DATA MEMBERS
		//
		///////////////////////////////////////////////////////////////////////////////////
		
		// type of stat to display - specified by the stat TYPES
		public var mType:int = -1;
		// used to store the string title of the stat
		public var mTitle:String = "";
		// used to store the display name of the stat
		public var mDisplayName:String = "";
		// the viewing time span for the stat
		public var mTimeSpan:int = -1;
		
		// stat TYPES
		public var KEYWORD_GOODNESS:int = 0;
		public var KEYWORD_PROFICIENCY:int = 1;
		public var KEYWORD_COUNT:int = 2;
		public var KEYWORD_OVERALL_POSITIVITY:int = 3;
		public var LINK_GOODNESS:int = 4;
		public var LINK_PROFICIENCY:int = 5;
		public var LINK_COUNT:int = 6;
		public var LINK_OVERALL_POSITIVITY:int = 7;
		
		// time span TYPES
		public var TIME_SPAN_1_WEEK:int = 100;
		public var TIME_SPAN_2_WEEKS:int = 101;
		public var TIME_SPAN_1_MONTH:int = 102;
		public var TIME_SPAN_6_MONTHS:int = 103;
		public var TIME_SPAN_9_MONTHS:int = 104;
		public var TIME_SPAN_1_YEAR:int = 105;
	}
}