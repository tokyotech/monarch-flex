/**
 * cWebsiteEntry
 * Created by : Mariusz Choroszy
 * 
 * 
 */ 

package
{
	public class cWebsiteEntry
	{
		/**
		 * 	
		 */ 
		public function cWebsiteEntry(id:int, name:String, URL:String)
		{
			mId = id;
			mName = name;
			mURL = URL;
		}
		
		/** The database id of the website **/
		public var mId:int;
		/** The name of the website **/
		public var mName:String;
		/** The URL of the website **/
		public var mURL:String;
	}
}