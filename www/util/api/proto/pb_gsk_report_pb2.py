# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: pb_gsk_report.proto

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
  name='pb_gsk_report.proto',
  package='',
  serialized_pb=_b('\n\x13pb_gsk_report.proto\"\xf4\x01\n\x15pb_report_t_base_info\x12\r\n\x05\x66_uin\x18\x01 \x01(\r\x12\r\n\x05\x66_clt\x18\x02 \x01(\x05\x12\x0f\n\x07\x66_phone\x18\x03 \x01(\t\x12\r\n\x05\x66_dye\x18\x04 \x01(\x05\x12\x0c\n\x04\x66_ip\x18\x05 \x01(\t\x12\x0e\n\x06\x66_name\x18\x06 \x01(\t\x12\x12\n\nf_province\x18\x07 \x01(\t\x12\x0e\n\x06\x66_city\x18\x08 \x01(\t\x12\x16\n\x0e\x66_company_type\x18\t \x01(\t\x12\x1a\n\x12\x66_years_of_working\x18\n \x01(\t\x12\x12\n\nf_job_type\x18\x0b \x01(\t\x12\x13\n\x0b\x66_job_title\x18\x0c \x01(\t\"\x97\x01\n\x12pb_report_t_client\x12\x14\n\x0c\x66_phone_info\x18\x01 \x01(\t\x12\x0c\n\x04\x66_os\x18\x02 \x01(\t\x12\x0c\n\x04\x66_sp\x18\x03 \x01(\t\x12\x11\n\tf_network\x18\x04 \x01(\t\x12\x11\n\tf_version\x18\x05 \x01(\x05\x12\x13\n\x0b\x66_client_id\x18\x06 \x01(\x05\x12\x14\n\x0c\x66_channel_id\x18\x07 \x01(\x05\"\xb8\x01\n\x12pb_report_t_report\x12\x12\n\nf_msg_type\x18\x01 \x01(\x05\x12\x0f\n\x07\x66_i_cmd\x18\x02 \x01(\x05\x12\x0f\n\x07\x66_s_cmd\x18\x03 \x01(\t\x12\x0e\n\x06\x66_time\x18\x04 \x01(\x05\x12\r\n\x05\x66_log\x18\x05 \x01(\t\x12&\n\x06\x66_info\x18\x06 \x01(\x0b\x32\x16.pb_report_t_base_info\x12%\n\x08\x66_client\x18\x07 \x01(\x0b\x32\x13.pb_report_t_client')
)
_sym_db.RegisterFileDescriptor(DESCRIPTOR)




_PB_REPORT_T_BASE_INFO = _descriptor.Descriptor(
  name='pb_report_t_base_info',
  full_name='pb_report_t_base_info',
  filename=None,
  file=DESCRIPTOR,
  containing_type=None,
  fields=[
    _descriptor.FieldDescriptor(
      name='f_uin', full_name='pb_report_t_base_info.f_uin', index=0,
      number=1, type=13, cpp_type=3, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_clt', full_name='pb_report_t_base_info.f_clt', index=1,
      number=2, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_phone', full_name='pb_report_t_base_info.f_phone', index=2,
      number=3, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_dye', full_name='pb_report_t_base_info.f_dye', index=3,
      number=4, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_ip', full_name='pb_report_t_base_info.f_ip', index=4,
      number=5, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_name', full_name='pb_report_t_base_info.f_name', index=5,
      number=6, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_province', full_name='pb_report_t_base_info.f_province', index=6,
      number=7, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_city', full_name='pb_report_t_base_info.f_city', index=7,
      number=8, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_company_type', full_name='pb_report_t_base_info.f_company_type', index=8,
      number=9, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_years_of_working', full_name='pb_report_t_base_info.f_years_of_working', index=9,
      number=10, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_job_type', full_name='pb_report_t_base_info.f_job_type', index=10,
      number=11, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_job_title', full_name='pb_report_t_base_info.f_job_title', index=11,
      number=12, type=9, cpp_type=9, label=1,
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
  serialized_start=24,
  serialized_end=268,
)


_PB_REPORT_T_CLIENT = _descriptor.Descriptor(
  name='pb_report_t_client',
  full_name='pb_report_t_client',
  filename=None,
  file=DESCRIPTOR,
  containing_type=None,
  fields=[
    _descriptor.FieldDescriptor(
      name='f_phone_info', full_name='pb_report_t_client.f_phone_info', index=0,
      number=1, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_os', full_name='pb_report_t_client.f_os', index=1,
      number=2, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_sp', full_name='pb_report_t_client.f_sp', index=2,
      number=3, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_network', full_name='pb_report_t_client.f_network', index=3,
      number=4, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_version', full_name='pb_report_t_client.f_version', index=4,
      number=5, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_client_id', full_name='pb_report_t_client.f_client_id', index=5,
      number=6, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_channel_id', full_name='pb_report_t_client.f_channel_id', index=6,
      number=7, type=5, cpp_type=1, label=1,
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
  serialized_start=271,
  serialized_end=422,
)


_PB_REPORT_T_REPORT = _descriptor.Descriptor(
  name='pb_report_t_report',
  full_name='pb_report_t_report',
  filename=None,
  file=DESCRIPTOR,
  containing_type=None,
  fields=[
    _descriptor.FieldDescriptor(
      name='f_msg_type', full_name='pb_report_t_report.f_msg_type', index=0,
      number=1, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_i_cmd', full_name='pb_report_t_report.f_i_cmd', index=1,
      number=2, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_s_cmd', full_name='pb_report_t_report.f_s_cmd', index=2,
      number=3, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_time', full_name='pb_report_t_report.f_time', index=3,
      number=4, type=5, cpp_type=1, label=1,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_log', full_name='pb_report_t_report.f_log', index=4,
      number=5, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_info', full_name='pb_report_t_report.f_info', index=5,
      number=6, type=11, cpp_type=10, label=1,
      has_default_value=False, default_value=None,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='f_client', full_name='pb_report_t_report.f_client', index=6,
      number=7, type=11, cpp_type=10, label=1,
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
  serialized_start=425,
  serialized_end=609,
)

_PB_REPORT_T_REPORT.fields_by_name['f_info'].message_type = _PB_REPORT_T_BASE_INFO
_PB_REPORT_T_REPORT.fields_by_name['f_client'].message_type = _PB_REPORT_T_CLIENT
DESCRIPTOR.message_types_by_name['pb_report_t_base_info'] = _PB_REPORT_T_BASE_INFO
DESCRIPTOR.message_types_by_name['pb_report_t_client'] = _PB_REPORT_T_CLIENT
DESCRIPTOR.message_types_by_name['pb_report_t_report'] = _PB_REPORT_T_REPORT

pb_report_t_base_info = _reflection.GeneratedProtocolMessageType('pb_report_t_base_info', (_message.Message,), dict(
  DESCRIPTOR = _PB_REPORT_T_BASE_INFO,
  __module__ = 'pb_gsk_report_pb2'
  # @@protoc_insertion_point(class_scope:pb_report_t_base_info)
  ))
_sym_db.RegisterMessage(pb_report_t_base_info)

pb_report_t_client = _reflection.GeneratedProtocolMessageType('pb_report_t_client', (_message.Message,), dict(
  DESCRIPTOR = _PB_REPORT_T_CLIENT,
  __module__ = 'pb_gsk_report_pb2'
  # @@protoc_insertion_point(class_scope:pb_report_t_client)
  ))
_sym_db.RegisterMessage(pb_report_t_client)

pb_report_t_report = _reflection.GeneratedProtocolMessageType('pb_report_t_report', (_message.Message,), dict(
  DESCRIPTOR = _PB_REPORT_T_REPORT,
  __module__ = 'pb_gsk_report_pb2'
  # @@protoc_insertion_point(class_scope:pb_report_t_report)
  ))
_sym_db.RegisterMessage(pb_report_t_report)


# @@protoc_insertion_point(module_scope)
