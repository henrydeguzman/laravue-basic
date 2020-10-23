// import request from '@/utils/request';
import Resource from '@/api/resource';

class ScheduleResource extends Resource {
  constructor(){
    super('schedules');
  }
}

export { ScheduleResource as default };
