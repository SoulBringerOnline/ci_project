# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: pb_gsk_req.proto

import sys
_b=sys.version_info[0]<3 and (lambda x:x) or (lambda x:x.encode('latin1'))
from google.protobuf import descriptor as _descriptor
from google.protobuf import message as _message
from google.protobuf import reflection as _reflection
from google.protobuf import symbol_database as _symbol_database
from google.protobuf import descriptor_pb2
# @@protoc_insertion_point(imports)

_sym_db = _symbol_database.Default()




DESCRIPTOR = _descriptor.FileDescriptor(
  name='pb_gsk_req.proto',
  package='',
  serialized_pb=_b('\n\x10pb_gsk_req.proto\"\x87\x01\n\x0fpb_req_t_attach\x12\x15\n\rf_attach_name\x18\x01 \x01(\t\x12\x15\n\rf_attach_type\x18\x02 \x01(\x05\x12\x14\n\x0c\x66_attach_url\x18\x03 \x01(\t\x12\x15\n\rf_attach_size\x18\x04 \x01(\x05\x12\x19\n\x11\x66_attach_playtime\x18\x05 \x01(\x05\"k\n\rpb_req_t_user\x12\r\n\x05\x66_uin\x18\x01 \x01(\r\x12\x0e\n\x06\x66_name\x18\x02 \x01(\t\x12\x0f\n\x07\x66_phone\x18\x03 \x01(\t\x12\x12\n\nf_job_type\x18\x04 \x01(\t\x12\x16\n\x0e\x66_join_content\x18\x05 \x01(\t\"\xcc\x02\n\x10pb_req_t_project\x12\x12\n\nf_prj_name\x18\x01 \x01(\t\x12\x12\n\nf_prj_type\x18\x02 \x01(\x05\x12\x13\n\x0b\x66_prj_image\x18\x03 \x01(\t\x12\x12\n\nf_province\x18\x04 \x01(\t\x12\x0e\n\x06\x66_city\x18\x05 \x01(\t\x12\x15\n\rf_prj_address\x18\x06 \x01(\t\x12\x14\n\x0c\x66_floor_area\x18\x07 \x01(\x05\x12\x13\n\x0b\x66_prj_begin\x18\x08 \x01(\x05\x12\x11\n\tf_prj_end\x18\t \x01(\x05\x12\x18\n\x10\x66_jianzhu_danwei\x18\n \x01(\t\x12\x18\n\x10\x66_shigong_danwei\x18\x0b \x01(\t\x12\x17\n\x0f\x66_jianli_danwei\x18\x0c \x01(\t\x12\x17\n\x0f\x66_fenbao_danwei\x18\r \x01(\t\x12\r\n\x05\x66_lat\x18\x0e \x01(\x01\x12\r\n\x05\x66_lon\x18\x0f \x01(\x01\"\xee\x01\n\x14pb_req_t_task_report\x12\x13\n\x0b\x66_report_id\x18\x01 \x01(\t\x12!\n\tf_sponsor\x18\x02 \x01(\x0b\x32\x0e.pb_req_t_user\x12\"\n\nf_reply_to\x18\x03 \x01(\x0b\x32\x0e.pb_req_t_user\x12\x0f\n\x07\x66_title\x18\x04 \x01(\t\x12\x11\n\tf_content\x18\x05 \x01(\t\x12\x16\n\x0e\x66_content_type\x18\x06 \x01(\x05\x12\'\n\rf_attach_list\x18\x07 \x03(\x0b\x32\x10.pb_req_t_attach\x12\x15\n\rf_report_time\x18\x08 \x01(\x05\"\xfe\x02\n\rpb_req_t_task\x12\x11\n\tf_task_id\x18\x01 \x01(\t\x12\x13\n\x0b\x66_task_type\x18\x02 \x01(\x05\x12\x10\n\x08\x66_prj_id\x18\x03 \x01(\t\x12\x13\n\x0b\x66_task_desc\x18\x04 \x01(\t\x12\x18\n\x10\x66_task_desc_type\x18\x05 \x01(\x05\x12\x12\n\nf_playtime\x18\x06 \x01(\x05\x12!\n\tf_sponsor\x18\x07 \x01(\x0b\x32\x0e.pb_req_t_user\x12!\n\tf_members\x18\x08 \x03(\x0b\x32\x0e.pb_req_t_user\x12\x12\n\nf_position\x18\t \x01(\t\x12\x15\n\rf_task_status\x18\n \x01(\x05\x12\'\n\rf_attach_list\x18\x0b \x03(\x0b\x32\x10.pb_req_t_attach\x12,\n\rf_report_list\x18\x0c \x03(\x0b\x32\x15.pb_req_t_task_report\x12\x14\n\x0c\x66_task_begin\x18\r \x01(\x05\x12\x12\n\nf_task_end\x18\x0e \x01(\x05\"V\n\x0fpb_req_t_report\x12\x14\n\x0c\x66_phone_info\x18\x01 \x01(\t\x12\x0c\n\x04\x66_os\x18\x02 \x01(\t\x12\x0c\n\x04\x66_sp\x18\x03 \x01(\t\x12\x11\n\tf_network\x18\x04 \x01(\t\"\xf4\x01\n\x0cpb_req_t_req\x12\r\n\x05\x66_cmd\x18\x01 \x01(\x05\x12\x10\n\x08\x66_i_args\x18\x02 \x03(\x05\x12\x10\n\x08\x66_s_args\x18\x03 \x03(\t\x12!\n\x07\x66_attch\x18\x04 \x03(\x0b\x32\x10.pb_req_t_attach\x12$\n\tf_project\x18\x05 \x01(\x0b\x32\x11.pb_req_t_project\x12$\n\x0c\x66_prj_member\x18\x06 \x03(\x0b\x32\x0e.pb_req_t_user\x12\x1e\n\x06\x66_task\x18\x07 \x01(\x0b\x32\x0e.pb_req_t_task\x12\"\n\x08\x66_report\x18\x08 \x01(\x0b\x32\x10.pb_req_t_report')
)
_sym_db.RegisterFileDescriptor(DESCRIPTOR)




_PB_REQ_T_ATTACH = _descriptor.Descriptor(
  name='pb_req_t_attach',
  full_name='pb_req_t_attach',
  filename=None,
  file=DESCRIPTOR,
  containing_type=None,
  fields=[
    _descriptor.FieldDescriptor(
      name='f_attach_name', full_name='pb_req_t_attach.f_attach_name', index=0,
      number=1, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_attach_type', full_name='pb_req_t_attach.f_attach_type', index=1,
      number=2, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_attach_url', full_name='pb_req_t_attach.f_attach_url', index=2,
      number=3, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_attach_size', full_name='pb_req_t_attach.f_attach_size', index=3,
      number=4, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_attach_playtime', full_name='pb_req_t_attach.f_attach_playtime', index=4,
      number=5, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
  ],
  extensions=[
  ],
  nested_types=[],
  enum_types=[
  ],
  options=None,
  is_extendable=False,
  extension_ranges=[],
  oneofs=[
  ],
  serialized_start=21,
  serialized_end=156,
)


_PB_REQ_T_USER = _descriptor.Descriptor(
  name='pb_req_t_user',
  full_name='pb_req_t_user',
  filename=None,
  file=DESCRIPTOR,
  containing_type=None,
  fields=[
    _descriptor.FieldDescriptor(
      name='f_uin', full_name='pb_req_t_user.f_uin', index=0,
      number=1, type=13, cpp_type=3, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_name', full_name='pb_req_t_user.f_name', index=1,
      number=2, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_phone', full_name='pb_req_t_user.f_phone', index=2,
      number=3, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_job_type', full_name='pb_req_t_user.f_job_type', index=3,
      number=4, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_join_content', full_name='pb_req_t_user.f_join_content', index=4,
      number=5, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
  ],
  extensions=[
  ],
  nested_types=[],
  enum_types=[
  ],
  options=None,
  is_extendable=False,
  extension_ranges=[],
  oneofs=[
  ],
  serialized_start=158,
  serialized_end=265,
)


_PB_REQ_T_PROJECT = _descriptor.Descriptor(
  name='pb_req_t_project',
  full_name='pb_req_t_project',
  filename=None,
  file=DESCRIPTOR,
  containing_type=None,
  fields=[
    _descriptor.FieldDescriptor(
      name='f_prj_name', full_name='pb_req_t_project.f_prj_name', index=0,
      number=1, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_prj_type', full_name='pb_req_t_project.f_prj_type', index=1,
      number=2, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_prj_image', full_name='pb_req_t_project.f_prj_image', index=2,
      number=3, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_province', full_name='pb_req_t_project.f_province', index=3,
      number=4, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_city', full_name='pb_req_t_project.f_city', index=4,
      number=5, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_prj_address', full_name='pb_req_t_project.f_prj_address', index=5,
      number=6, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_floor_area', full_name='pb_req_t_project.f_floor_area', index=6,
      number=7, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_prj_begin', full_name='pb_req_t_project.f_prj_begin', index=7,
      number=8, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_prj_end', full_name='pb_req_t_project.f_prj_end', index=8,
      number=9, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_jianzhu_danwei', full_name='pb_req_t_project.f_jianzhu_danwei', index=9,
      number=10, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_shigong_danwei', full_name='pb_req_t_project.f_shigong_danwei', index=10,
      number=11, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_jianli_danwei', full_name='pb_req_t_project.f_jianli_danwei', index=11,
      number=12, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_fenbao_danwei', full_name='pb_req_t_project.f_fenbao_danwei', index=12,
      number=13, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_lat', full_name='pb_req_t_project.f_lat', index=13,
      number=14, type=1, cpp_type=5, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_lon', full_name='pb_req_t_project.f_lon', index=14,
      number=15, type=1, cpp_type=5, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
  ],
  extensions=[
  ],
  nested_types=[],
  enum_types=[
  ],
  options=None,
  is_extendable=False,
  extension_ranges=[],
  oneofs=[
  ],
  serialized_start=268,
  serialized_end=600,
)


_PB_REQ_T_TASK_REPORT = _descriptor.Descriptor(
  name='pb_req_t_task_report',
  full_name='pb_req_t_task_report',
  filename=None,
  file=DESCRIPTOR,
  containing_type=None,
  fields=[
    _descriptor.FieldDescriptor(
      name='f_report_id', full_name='pb_req_t_task_report.f_report_id', index=0,
      number=1, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_sponsor', full_name='pb_req_t_task_report.f_sponsor', index=1,
      number=2, type=11, cpp_type=10, label=1,
      has_default_value=False, default_value=None,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_reply_to', full_name='pb_req_t_task_report.f_reply_to', index=2,
      number=3, type=11, cpp_type=10, label=1,
      has_default_value=False, default_value=None,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_title', full_name='pb_req_t_task_report.f_title', index=3,
      number=4, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_content', full_name='pb_req_t_task_report.f_content', index=4,
      number=5, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_content_type', full_name='pb_req_t_task_report.f_content_type', index=5,
      number=6, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_attach_list', full_name='pb_req_t_task_report.f_attach_list', index=6,
      number=7, type=11, cpp_type=10, label=3,
      has_default_value=False, default_value=[],
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_report_time', full_name='pb_req_t_task_report.f_report_time', index=7,
      number=8, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
  ],
  extensions=[
  ],
  nested_types=[],
  enum_types=[
  ],
  options=None,
  is_extendable=False,
  extension_ranges=[],
  oneofs=[
  ],
  serialized_start=603,
  serialized_end=841,
)


_PB_REQ_T_TASK = _descriptor.Descriptor(
  name='pb_req_t_task',
  full_name='pb_req_t_task',
  filename=None,
  file=DESCRIPTOR,
  containing_type=None,
  fields=[
    _descriptor.FieldDescriptor(
      name='f_task_id', full_name='pb_req_t_task.f_task_id', index=0,
      number=1, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_task_type', full_name='pb_req_t_task.f_task_type', index=1,
      number=2, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_prj_id', full_name='pb_req_t_task.f_prj_id', index=2,
      number=3, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_task_desc', full_name='pb_req_t_task.f_task_desc', index=3,
      number=4, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_task_desc_type', full_name='pb_req_t_task.f_task_desc_type', index=4,
      number=5, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_playtime', full_name='pb_req_t_task.f_playtime', index=5,
      number=6, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_sponsor', full_name='pb_req_t_task.f_sponsor', index=6,
      number=7, type=11, cpp_type=10, label=1,
      has_default_value=False, default_value=None,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_members', full_name='pb_req_t_task.f_members', index=7,
      number=8, type=11, cpp_type=10, label=3,
      has_default_value=False, default_value=[],
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_position', full_name='pb_req_t_task.f_position', index=8,
      number=9, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_task_status', full_name='pb_req_t_task.f_task_status', index=9,
      number=10, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_attach_list', full_name='pb_req_t_task.f_attach_list', index=10,
      number=11, type=11, cpp_type=10, label=3,
      has_default_value=False, default_value=[],
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_report_list', full_name='pb_req_t_task.f_report_list', index=11,
      number=12, type=11, cpp_type=10, label=3,
      has_default_value=False, default_value=[],
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_task_begin', full_name='pb_req_t_task.f_task_begin', index=12,
      number=13, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_task_end', full_name='pb_req_t_task.f_task_end', index=13,
      number=14, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
  ],
  extensions=[
  ],
  nested_types=[],
  enum_types=[
  ],
  options=None,
  is_extendable=False,
  extension_ranges=[],
  oneofs=[
  ],
  serialized_start=844,
  serialized_end=1226,
)


_PB_REQ_T_REPORT = _descriptor.Descriptor(
  name='pb_req_t_report',
  full_name='pb_req_t_report',
  filename=None,
  file=DESCRIPTOR,
  containing_type=None,
  fields=[
    _descriptor.FieldDescriptor(
      name='f_phone_info', full_name='pb_req_t_report.f_phone_info', index=0,
      number=1, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_os', full_name='pb_req_t_report.f_os', index=1,
      number=2, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_sp', full_name='pb_req_t_report.f_sp', index=2,
      number=3, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_network', full_name='pb_req_t_report.f_network', index=3,
      number=4, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
  ],
  extensions=[
  ],
  nested_types=[],
  enum_types=[
  ],
  options=None,
  is_extendable=False,
  extension_ranges=[],
  oneofs=[
  ],
  serialized_start=1228,
  serialized_end=1314,
)


_PB_REQ_T_REQ = _descriptor.Descriptor(
  name='pb_req_t_req',
  full_name='pb_req_t_req',
  filename=None,
  file=DESCRIPTOR,
  containing_type=None,
  fields=[
    _descriptor.FieldDescriptor(
      name='f_cmd', full_name='pb_req_t_req.f_cmd', index=0,
      number=1, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_i_args', full_name='pb_req_t_req.f_i_args', index=1,
      number=2, type=5, cpp_type=1, label=3,
      has_default_value=False, default_value=[],
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_s_args', full_name='pb_req_t_req.f_s_args', index=2,
      number=3, type=9, cpp_type=9, label=3,
      has_default_value=False, default_value=[],
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_attch', full_name='pb_req_t_req.f_attch', index=3,
      number=4, type=11, cpp_type=10, label=3,
      has_default_value=False, default_value=[],
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_project', full_name='pb_req_t_req.f_project', index=4,
      number=5, type=11, cpp_type=10, label=1,
      has_default_value=False, default_value=None,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_prj_member', full_name='pb_req_t_req.f_prj_member', index=5,
      number=6, type=11, cpp_type=10, label=3,
      has_default_value=False, default_value=[],
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_task', full_name='pb_req_t_req.f_task', index=6,
      number=7, type=11, cpp_type=10, label=1,
      has_default_value=False, default_value=None,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_report', full_name='pb_req_t_req.f_report', index=7,
      number=8, type=11, cpp_type=10, label=1,
      has_default_value=False, default_value=None,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
  ],
  extensions=[
  ],
  nested_types=[],
  enum_types=[
  ],
  options=None,
  is_extendable=False,
  extension_ranges=[],
  oneofs=[
  ],
  serialized_start=1317,
  serialized_end=1561,
)

_PB_REQ_T_TASK_REPORT.fields_by_name['f_sponsor'].message_type = _PB_REQ_T_USER
_PB_REQ_T_TASK_REPORT.fields_by_name['f_reply_to'].message_type = _PB_REQ_T_USER
_PB_REQ_T_TASK_REPORT.fields_by_name['f_attach_list'].message_type = _PB_REQ_T_ATTACH
_PB_REQ_T_TASK.fields_by_name['f_sponsor'].message_type = _PB_REQ_T_USER
_PB_REQ_T_TASK.fields_by_name['f_members'].message_type = _PB_REQ_T_USER
_PB_REQ_T_TASK.fields_by_name['f_attach_list'].message_type = _PB_REQ_T_ATTACH
_PB_REQ_T_TASK.fields_by_name['f_report_list'].message_type = _PB_REQ_T_TASK_REPORT
_PB_REQ_T_REQ.fields_by_name['f_attch'].message_type = _PB_REQ_T_ATTACH
_PB_REQ_T_REQ.fields_by_name['f_project'].message_type = _PB_REQ_T_PROJECT
_PB_REQ_T_REQ.fields_by_name['f_prj_member'].message_type = _PB_REQ_T_USER
_PB_REQ_T_REQ.fields_by_name['f_task'].message_type = _PB_REQ_T_TASK
_PB_REQ_T_REQ.fields_by_name['f_report'].message_type = _PB_REQ_T_REPORT
DESCRIPTOR.message_types_by_name['pb_req_t_attach'] = _PB_REQ_T_ATTACH
DESCRIPTOR.message_types_by_name['pb_req_t_user'] = _PB_REQ_T_USER
DESCRIPTOR.message_types_by_name['pb_req_t_project'] = _PB_REQ_T_PROJECT
DESCRIPTOR.message_types_by_name['pb_req_t_task_report'] = _PB_REQ_T_TASK_REPORT
DESCRIPTOR.message_types_by_name['pb_req_t_task'] = _PB_REQ_T_TASK
DESCRIPTOR.message_types_by_name['pb_req_t_report'] = _PB_REQ_T_REPORT
DESCRIPTOR.message_types_by_name['pb_req_t_req'] = _PB_REQ_T_REQ

pb_req_t_attach = _reflection.GeneratedProtocolMessageType('pb_req_t_attach', (_message.Message,), dict(
  DESCRIPTOR = _PB_REQ_T_ATTACH,
  __module__ = 'pb_gsk_req_pb2'
  # @@protoc_insertion_point(class_scope:pb_req_t_attach)
  ))
_sym_db.RegisterMessage(pb_req_t_attach)

pb_req_t_user = _reflection.GeneratedProtocolMessageType('pb_req_t_user', (_message.Message,), dict(
  DESCRIPTOR = _PB_REQ_T_USER,
  __module__ = 'pb_gsk_req_pb2'
  # @@protoc_insertion_point(class_scope:pb_req_t_user)
  ))
_sym_db.RegisterMessage(pb_req_t_user)

pb_req_t_project = _reflection.GeneratedProtocolMessageType('pb_req_t_project', (_message.Message,), dict(
  DESCRIPTOR = _PB_REQ_T_PROJECT,
  __module__ = 'pb_gsk_req_pb2'
  # @@protoc_insertion_point(class_scope:pb_req_t_project)
  ))
_sym_db.RegisterMessage(pb_req_t_project)

pb_req_t_task_report = _reflection.GeneratedProtocolMessageType('pb_req_t_task_report', (_message.Message,), dict(
  DESCRIPTOR = _PB_REQ_T_TASK_REPORT,
  __module__ = 'pb_gsk_req_pb2'
  # @@protoc_insertion_point(class_scope:pb_req_t_task_report)
  ))
_sym_db.RegisterMessage(pb_req_t_task_report)

pb_req_t_task = _reflection.GeneratedProtocolMessageType('pb_req_t_task', (_message.Message,), dict(
  DESCRIPTOR = _PB_REQ_T_TASK,
  __module__ = 'pb_gsk_req_pb2'
  # @@protoc_insertion_point(class_scope:pb_req_t_task)
  ))
_sym_db.RegisterMessage(pb_req_t_task)

pb_req_t_report = _reflection.GeneratedProtocolMessageType('pb_req_t_report', (_message.Message,), dict(
  DESCRIPTOR = _PB_REQ_T_REPORT,
  __module__ = 'pb_gsk_req_pb2'
  # @@protoc_insertion_point(class_scope:pb_req_t_report)
  ))
_sym_db.RegisterMessage(pb_req_t_report)

pb_req_t_req = _reflection.GeneratedProtocolMessageType('pb_req_t_req', (_message.Message,), dict(
  DESCRIPTOR = _PB_REQ_T_REQ,
  __module__ = 'pb_gsk_req_pb2'
  # @@protoc_insertion_point(class_scope:pb_req_t_req)
  ))
_sym_db.RegisterMessage(pb_req_t_req)


# @@protoc_insertion_point(module_scope)